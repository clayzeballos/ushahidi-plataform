#!/usr/bin/python
from Wiki import *
from misaka import Markdown, HtmlRenderer

wiki = Wiki()
md = Markdown(HtmlRenderer())

if len(sys.argv) < 4:
   exit("Usage: " + sys.argv[0] + " spacekey pagetitle markdownfile")

spacekey = sys.argv[1]
pagetitle = sys.argv[2]
markdownfile = sys.argv[3]

# Open markdown file
f = open(markdownfile)
md_title = f.readline().replace("# ", "").replace("\n", "")
md_content = f.read()

# Render markdown to HTML
new_content = md.render(md_content)

# Find current wiki page
page = wiki.server.confluence2.getPage(wiki.token, spacekey, pagetitle)
if page is None:
   exit("Could not find page " + spacekey + ":" + pagetitle)

# @todo update title too
# Update content
page['content'] = new_content
# Save
wiki.server.confluence2.storePage(wiki.token, page)