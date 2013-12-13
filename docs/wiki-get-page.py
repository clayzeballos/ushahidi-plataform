#!/usr/bin/python
from Wiki import *
import html2text

wiki = Wiki()

h = html2text.HTML2Text()

if len(sys.argv) < 3:
   exit("Usage: " + sys.argv[0] + " spacekey pagetitle")

spacekey = sys.argv[1]
pagetitle = sys.argv[2]

page = wiki.server.confluence2.getPage(wiki.token, spacekey, pagetitle)
if page is None:
   exit("Could not find page " + spacekey + ":" + pagetitle)

print "# " + page['title']
print ""
print h.handle(page['content'])
