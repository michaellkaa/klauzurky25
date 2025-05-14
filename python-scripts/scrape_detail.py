import requests
from bs4 import BeautifulSoup

url = "https://www.vysokeskoly.com/PS/Vysoke-skoly"

response = requests.get(url)
response.encoding = 'windows-1250'
soup = BeautifulSoup(response.text, 'html.parser')

# Najdeme všechny bloky škol
bloky_skol = soup.select(".ListSkola")

for blok in bloky_skol:
    # Najdeme odkaz
    odkaz_element = blok.select_one("a.fullOdkaz")
    if not odkaz_element:
        continue
    odkaz = odkaz_element['href']

    nazev_element = blok.select_one(".nazev h3")
    if not nazev_element:
        continue
    nazev = nazev_element.get_text(strip=True)

    print(f"Název: {nazev}")
    print(f"Odkaz: {odkaz}\n")
    