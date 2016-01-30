# [START imports]
import os
import urllib

from google.appengine.api import users
from google.appengine.ext import ndb

import jinja2
import webapp2


JINJA_ENVIRONMENT = jinja2.Environment(
    loader=jinja2.FileSystemLoader(os.path.dirname(__file__)),
    extensions=['jinja2.ext.autoescape'],
    autoescape=True)
# [END imports]


# [START main_page]
class MainPage(webapp2.RequestHandler):
      user = users.get_current_user()
      
      if user:
          url = users.create_logout_url(self.request.uri)
          url_linktext = 'Logout'
      else:
          url = users.create_login_url(self.request.uri)
          url_linktext = 'Login'
          
       template_values = {
          'user': user,
          'greetings': greetings,
          'guestbook_name': urllib.quote_plus(guestbook_name),
          'url': url,
          'url_linktext': url_linktext,
      }

       template = JINJA_ENVIRONMENT.get_template('index.html')
      self.response.write(template.render(template_values))
# [END main_page]
   
app = webapp2.WSGIApplication([
    ('/', MainPage),
    ('/sign', Guestbook),
], debug=True)
