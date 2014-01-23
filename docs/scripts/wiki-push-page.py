#!/usr/bin/python
from Wiki import *
import urllib2
import json

wiki = Wiki()
if len(sys.argv) < 4:
   exit("Usage: " + sys.argv[0] + " spacekey pagetitle markdownfile")

spacekey = sys.argv[1]
pagetitle = sys.argv[2]
markdownfile = sys.argv[3]

# Open markdown file
f = open(markdownfile)
md_title = f.readline().replace("# ", "").replace("\n", "")
md_content = f.read()

# Render markdown via github api
jdata = json.dumps({
  "text": md_content,
  "mode": "markdown",
  "context": "ushahidi/Lamu"
})
response = urllib2.urlopen("https://api.github.com/markdown", jdata)
new_content = response.read()

# Find current wiki page
page = wiki.server.confluence2.getPage(wiki.token, spacekey, pagetitle)
if page is None:
   exit("Could not find page " + spacekey + ":" + pagetitle)

# Update title and content
page['title'] = md_title
page['content'] = new_content

# Save
wiki.server.confluence2.updatePage(wiki.token, page, {"versionComment" : "Sync update from github source"})