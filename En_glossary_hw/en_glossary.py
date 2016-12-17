import urllib.request
from docx import Document
from bs4 import BeautifulSoup
import re
import random

def grab_info(term):
	url = "http://pc.net/glossary/definition/" + term
	html = BeautifulSoup(urllib.request.urlopen(url).read(), 'html.parser')
	word = html.find('h2').getText()

	try:
		definition = html.find('div',{'id':'definition'}).getText()
		definition = re.match(r'(?:[^.:;]+[.:;]){5}', definition).group()
	except AttributeError:
		definition = html.find('div',{'id':'definition'}).getText()

	return (word,definition)

def generate_url(alpha):
	counter = 0
	url = "http://pc.net/glossary/browse/" + alpha
	html = BeautifulSoup(urllib.request.urlopen(url).read(), 'html.parser')
	table = html.find("table", attrs={"class":"list"}).findAll('td', {'class' : 'term'})
	for el in table:
		table[counter] = table[counter].getText().lower()
		table[counter] = ''.join(e for e in table[counter] if e.isalnum())
		counter += 1
	return table

words = []
i = ord('a')
print("working...")
while i <= ord('z'):
	words.extend(generate_url(chr(i)))
	i+=1

random.shuffle(words)
i = 0
while i < 50:
	print(i+1)
	print(words[i])
	result = grab_info(words[i])
	print(result[0],result[1])
	print("\n")
	i+=1
