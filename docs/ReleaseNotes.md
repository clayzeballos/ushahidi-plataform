# Release Notes

* [Alpha 3](#alpha-3-v300-alpha3)
* [Developer Release](#developer-release-v300-alpha1)

## Alpha 3 (v3.0.0-alpha.3)

There's a bunch of new things in this release, you can search and edit posts,
edit site settings, manage users, pull messages from SMS and turn them in to
posts. One of the simplest but most major improvements: you can log in,
register and log out! And you can access the public site without logging in at
all! As with the previous release you should still be able to get V3
installed, create and delete posts, view a list of posts and drill down to
individual post pages

### What's not working:
* Uploading images on posts
* Showing custom form field on posts
* User profile in the workspace menu still display dummy content
* Posts still display dummy images
* Permission checks in the UI - we check permissions thoroughly throughout the
API however this isn't always reflected in the UI. This means you'll sometimes
see a UI for editing something (ie. users) but be unable to actually  load an
data or unable to edit the data.
* Related posts - always shows the most recent 3 posts
* Media - We're just using fake images at the moment, there's no way to upload
new ones
* Custom forms - these exist in the API, but there's no UI for managing them.

### How do I get admin access?

The default install creates a user 'demo' with password 'testing'. This user
has admin privileges. Once logged in this user can create further user
accounts or give others admin permissions too.

### Authorization (aka. why does it ask me to 'Authorize This Request'?)

When logging in you still get a standard OAuth authorization screen. This is
because our UI is using the API directly, and the standard authorization
flows. We've improved this a lot since last release and we're working on
getting rid of the authorize screen completely for the default UI client.

### How do I pull in SMS or Email

This is working but the config is still in code. The main config is covered in
```application/config/data-providers.php```. We'll be publishing a detailed
guide on how to do this soon!

## Developer Release (v3.0.0-alpha.1)

We've built a working API and a basic JS powered UI, however there are still a lot of rough edges you should know about.

### What's working:

* Post listings - listings work and load real data. They're page-able and sort-able, but not search-able yet.
* Post detail pages - these work and load real data. However they don't render custom form data yet, and the images are faked.
* Post create form - well kind of. It should mostly work, but there are definitely still bugs with this.
* Posts delete

### What's not working:

* Searching posts
* Workspace menu - the menu is there, but none of the links do anything
* Login
* Register
* Related posts - always shows the most recent 3 posts
* Media - We're just using fake images at the moment, there's no way to upload new ones
* Custom forms - these exist in the API, but there's no UI for managing them.

### Looks like it works, but doesn't

There are a bunch of views built into that app that are really just design prototypes. They look real, but they're not powered by real data.

* Sets listing
* Set details
* Editing posts
* Adding posts to sets

### Authorization (aka. why does it keep asking me to 'Authorize This Request'?)

Our authorization is currently a quick hack. The JS app hits the API directly, and this means it has to use a standard OAuth authorization flow. At the moment thats a plain unstyled bit of UI: the ugly 'Authorize This Request' screen. On top of that the default token time out is only an hour - so you'll often hit the authorize screen quite a few times while developing.

This is temporary, we're working on a real solution, but for now please bear with us.