import requests
from bs4 import BeautifulSoup
import sqlite3

# URL stránky s informacemi o univerzitě
url = "https://www.vysokeskoly.com/vysoke-skoly-1/masarykova-univerzita"

# Odeslání HTTP požadavku a získání obsahu stránky
response = requests.get(url)

# Kontrola, zda je stránka dostupná
if response.status_code != 200:
    print(f"Chyba při získávání stránky: {response.status_code}")
    exit()

# Zpracování obsahu stránky pomocí BeautifulSoup
soup = BeautifulSoup(response.content, "html.parser")

# Vyhledání sekce fakulty
fakulty_section = soup.find("div", class_="sekce-fakulty")

# Zkontrolujme, zda sekce fakulty byla nalezena
if fakulty_section is None:
    print("Sekce fakulty nebyla nalezena. Zkontroluj strukturu stránky.")
    exit()

# Vyhledání všech odkazů na fakulty
fakulta_links = fakulty_section.find_all("a", class_="fakultaDetail")

# Zkontrolujme, zda jsme našli odkazy na fakulty
if not fakulta_links:
    print("Nebyl nalezen žádný odkaz na fakulty. Zkontroluj strukturu stránky.")
    exit()

# Seznam názvů fakult
fakulty_names = [link.text.strip() for link in fakulta_links]

# Spojení názvů fakult do jednoho řetězce odděleného čárkami
fields = ", ".join(fakulty_names)

# Vytvoření připojení k SQLite databázi
conn = sqlite3.connect("university_data.db")
cursor = conn.cursor()

# Vytvoření tabulky pro fakulty, pokud ještě neexistuje
cursor.execute("""
CREATE TABLE IF NOT EXISTS fields (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT
)
""")

# Uložení názvů fakult do databáze
for fakulta in fakulty_names:
    cursor.execute("INSERT INTO fields (name) VALUES (?)", (fakulta,))

# Uložení změn a uzavření připojení
conn.commit()
conn.close()

# Výpis názvů fakult
print(f"Fakulty: {fields}")
