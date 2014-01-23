#!/usr/bin/python
import sys, xmlrpclib, ConfigParser

"""
Init class to set up connection to confluence wiki
3vilcomesinmanyguises
http://megatokyo.com/strip/287
"""
class Wiki:
	def __init__(self):
		config = self.config = ConfigParser.ConfigParser()
		#config.readfp(open('defaults.cfg'))
		config.readfp(open('../.wiki.cfg'))

		self.server = xmlrpclib.ServerProxy('https://wiki.ushahidi.com/rpc/xmlrpc')
		self.token = self.server.confluence2.login(config.get('auth', 'username'), config.get('auth', 'password'))
