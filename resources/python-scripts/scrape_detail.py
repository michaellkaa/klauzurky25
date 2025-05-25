import requests
from bs4 import BeautifulSoup
import re
import mysql.connector
import os

def insert_into_database(uni):
    try:
        if not uni.get('name') or not uni.get('location'):
            print(f"Přeskočeno (chybí jméno nebo město): {uni.get('name')} / {uni.get('location')}")
            return

        connection = mysql.connector.connect(
            host='89.168.43.83',
            user='michaelka',
            password='michaelka1',
            database='michaelka_klauzury_html',
            charset='utf8mb4',
            collation='utf8mb4_general_ci'
        )
        if connection.is_connected():
            cursor = connection.cursor()

            query = """
                INSERT INTO universities 
                (name, address, location, website, facebook, twitter, instagram, youtube, linkedin, about, email, phone, logo_url, banner_url, type, field)
                VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
            """
            values = (
                uni['name'],
                uni.get('address', ''),
                uni['location'],
                uni.get('website', ''),
                uni.get('facebook', ''),
                uni.get('twitter', ''),
                uni.get('instagram', ''),
                uni.get('youtube', ''),
                uni.get('linkedin', ''),
                uni.get('about', ''),
                uni.get('email', ''),
                uni.get('phone', ''),
                uni.get('logo', ''),
                uni.get('banner', ''),
                uni.get('type', ''),
                uni.get('field', ''),
                
            )

            cursor.execute(query, values)
            connection.commit()
            print(f"Uloženo do databáze: {uni['name']}")
    except Exception as e:
        print(f"Chyba při ukládání: {e}")
    finally:
        if 'connection' in locals() and connection.is_connected():
            cursor.close()
            connection.close()


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

    web = ""
    for a in detail_soup.select("p.kontakty2 a[href^='http']"):
        href = a.get("href", "")
        if not any(x in href for x in ["facebook", "instagram", "twitter", "youtube", "linkedin"]):
            web = href
            break

    email = telefon = ""
    kontakty = detail_soup.select(".kontakty2")
    for kontakt_blok in kontakty:
        text = kontakt_blok.get_text(separator="\n", strip=True)
        email_match = re.search(r"[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}", text)
        if email_match:
            email = email_match.group(0)
        telefon_match = re.search(r"\+?\d{3} ?\d{3} ?\d{3}", text)
        if telefon_match:
            telefon = telefon_match.group(0)

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

    banner_url = logo_url = ""
    banner_elem = detail_soup.select_one(".FullBigBanner .big-skolaImg img")
    if banner_elem:
        banner_url = "https://www.vysokeskoly.com" + banner_elem.get("src", "")

    logo_elem = detail_soup.select_one(".FullBigBanner .big-skolaLogo img")
    if logo_elem:
        logo_url = "https://www.vysokeskoly.com" + logo_elem.get("src", "")

    uni_data = {
        'name': nazev,
        'address': lokace,
        'location': mesto,
        'website': web,
        'facebook': facebook,
        'instagram': instagram,
        'twitter': twitter,
        'youtube': youtube,
        'linkedin': linkedin,
        'about': popis,
        'email': email,
        'phone': telefon,
        'logo': logo_url,
        'banner': banner_url,
        'type': stitek,
        'field': fakulty_text,
    }

    insert_into_database(uni_data)

    #print(f"Název: {nazev}")
    #print(f"Odkaz: {odkaz}")
    #print(f"Lokace: {lokace}")
    #print(f"Město: {mesto}")
    #print(f"Typ školy: {stitek}")
    #print(f"Obory: {fakulty_text}")
    #print(f"Popis: {popis}")
    #print(f"Web: {web}")
    #print(f"E-mail: {email}")
    #print(f"Telefon: {telefon}")
    #print(f"Facebook: {facebook}")
    #print(f"Instagram: {instagram}")
    #print(f"Twitter: {twitter}")
    #print(f"YouTube: {youtube}")
    #print(f"LinkedIn: {linkedin}")
    #print(f"Obrázky: {banner_path if banner_elem else 'žádný banner'}, {logo_path if logo_elem else 'žádné logo'}\n")
