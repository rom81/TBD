# http://3adly.blogspot.com/2012/11/google-app-engine-facebook-application.html

class ProfileHandler(BaseHandler):
    def get(self):
        self.show_profile()
 
    def post(self):
        self.show_profile()
         
    def show_profile(self):
        profile = ''
        current_user = self.current_user
        if current_user:
            graph = facebook.GraphAPI(current_user.access_token)
            profile = profile = graph.get_object("me")
        path = os.path.join(os.path.dirname(__file__), "templates/profile.html")
        args = dict(current_user=current_user,
                    facebook_app_id=FACEBOOK_APP_ID,
                    profile=profile)
        self.response.out.write(template.render(path, args))
