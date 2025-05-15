import requests
from bs4 import BeautifulSoup
import re

url = "https://www.vysokeskoly.com/PS/Vysoke-skoly"

response = requests.get(url)
response.encoding = 'windows-1250'
soup = BeautifulSoup(response.text, 'html.parser')

bloky_skol = soup.select(".ListSkola")

for blok in bloky_skol:
    odkaz_element = blok.select_one("a.fullOdkaz")
    if not odkaz_element:
        continue
    odkaz = odkaz_element['href']

    nazev_element = blok.select_one(".nazev h3")
    if not nazev_element:
        continue
    nazev = nazev_element.get_text(strip=True)

    adresa_element = blok.select_one(".skolAdresa")
    lokace = adresa_element.get_text(strip=True) if adresa_element else ""

    mesto = ""
    if lokace:
        match = re.search(r"\d{3}\s?\d{2}\s*(.+)", lokace)
        if match:
            mesto = match.group(1).strip()
        if mesto.lower().startswith("praha"):
            mesto = "Praha"

    fakulty_a = blok.select("ul.seznamFakult li a")
    fakulty = [a.get("title", a.get_text(strip=True)) for a in fakulty_a]
    fakulty_text = ", ".join(fakulty)

    stitek_element = blok.select_one(".stitky .stitek.typ-skoly")
    stitek = stitek_element.get_text(strip=True) if stitek_element else ""

    print(f"Název: {nazev}")
    print(f"Odkaz: {odkaz}")
    print(f"Lokace: {lokace}")
    print(f"Město: {mesto}")
    print(f"Typ školy: {stitek}")
    print(f"Obory: {fakulty_text}\n")
