#!/usr/bin/python
import sys, xmlrpclib, ConfigParser

class Wiki:
	def __init__(self):
		config = self.config = ConfigParser.ConfigParser()
		#config.readfp(open('defaults.cfg'))
		config.readfp(open('../.wiki.cfg'))

		self.server = xmlrpclib.ServerProxy('https://wiki.ushahidi.com/rpc/xmlrpc')
		self.token = self.server.confluence2.login(config.get('auth', 'username'), config.get('auth', 'password'))
