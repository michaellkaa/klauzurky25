import requests
from bs4 import BeautifulSoup

# Cílová URL
url = "https://www.vysokeskoly.com/PS/Vysoke-skoly"

# Získání HTML stránky
response = requests.get(url)
soup = BeautifulSoup(response.text, 'html.parser')

# Najdeme všechny bloky škol
bloky_skol = soup.select(".ListSkola")

# Projdeme každý blok
for blok in bloky_skol:
    # Najdeme odkaz
    odkaz_element = blok.select_one("a.fullOdkaz")
    if not odkaz_element:
        continue
    odkaz = odkaz_element['href']

    # Najdeme název školy
    nazev_element = blok.select_one(".nazev h3")
    if not nazev_element:
        continue
    nazev = nazev_element.get_text(strip=True)

    print(f"Název: {nazev}")
    print(f"Odkaz: {odkaz}\n")
