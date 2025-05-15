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

    detail_response = requests.get(odkaz)
    detail_response.encoding = 'windows-1250'
    detail_soup = BeautifulSoup(detail_response.text, 'html.parser')

    popis_element = detail_soup.select_one(".BoxAnot p")
    popis = popis_element.get_text(strip=True) if popis_element else "Popis nenalezen"

    facebook = instagram = twitter = youtube = linkedin = ""
    social_links = detail_soup.select("ul.FKsocial a")
    for link in social_links:
        href = link.get("href", "")
        classes = link.get("class", [])
        if "ico-facebook" in classes:
            facebook = href
        elif "ico-instagram" in classes:
            instagram = href
        elif "ico-twitter" in classes:
            twitter = href
        elif "ico-youtube" in classes:
            youtube = href
        elif "ico-linkedin" in classes:
            linkedin = href

    print(f"Název: {nazev}")
    print(f"Odkaz: {odkaz}")
    print(f"Lokace: {lokace}")
    print(f"Město: {mesto}")
    print(f"Typ školy: {stitek}")
    print(f"Obory: {fakulty_text}")
    print(f"Popis: {popis}")
    print(f"Facebook: {facebook}")
    print(f"Instagram: {instagram}")
    print(f"Twitter: {twitter}")
    print(f"YouTube: {youtube}")
    print(f"LinkedIn: {linkedin}\n")
