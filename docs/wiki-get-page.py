#!/usr/bin/python
from Wiki import *
import html2text
from lxml import etree

wiki = Wiki()

h = html2text.HTML2Text()

if len(sys.argv) < 3:
   exit("Usage: " + sys.argv[0] + " spacekey pagetitle")

spacekey = sys.argv[1]
pagetitle = sys.argv[2]

page = wiki.server.confluence2.getPage(wiki.token, spacekey, pagetitle)
if page is None:
   exit("Could not find page " + spacekey + ":" + pagetitle)

# Wrap Confluence XML in headers and such
strWrapperTop = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>" \
  "<!DOCTYPE ac:confluence SYSTEM \"confluence-all.dtd\" [ " \
  "<!ENTITY clubs    \"&#9827;\">" \
  "<!ENTITY nbsp   \"&#160;\">" \
  "<!ENTITY ndash   \"&#8211;\">" \
  "<!ENTITY mdash   \"&#8212;\">" \
  " ]>" \
  "<ac:confluence xmlns:ac=\"http://www.atlassian.com/schema/confluence/4/ac/\" xmlns:ri=\"http://www.atlassian.com/schema/confluence/4/ri/\" xmlns=\"http://www.atlassian.com/schema/confluence/4/\">"
strWrapperBottom = "</ac:confluence>"
strConfluenceXMLDoc = strWrapperTop + page['content'] + strWrapperBottom

doc = etree.fromstring(strConfluenceXMLDoc)
#xslt_tree = etree.parse("confluence2markdown.xsl")
xslt_tree = etree.parse("confluence2xhtml.xsl")
transform = etree.XSLT(xslt_tree)
result = transform(doc)

print "# " + page['title']
print ""
print h.handle(unicode(result).encode("ascii","ignore"))
