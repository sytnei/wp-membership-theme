# wp-membership-theme

We would like you to spend no more than 4 hours working on this test project and to build a WordPress plugin that does the following:


Registers a custom post type named "Team Member". This CPT should support title, content, and featured image.

Registers a custom taxonomy for "Department". This taxonomy should be for the "Team Member" CPT only.

Register custom meta fields for Position, Twitter URL, and Facebook URL.

You're welcome to utilize a custom fields library (like CMB2) for this, or not, your choice. We do need a dedicated admin UI for setting these values.

Make sure you're taking time to sanitize these values before storing them in the database

Make sure you're escaping these values before output on the front-end 

Creates a custom template with the appropriate markup

Creates the required styles to support the template layout

Utilizes JavaScript for the show/hide toggle behavior described below.


The custom template should render the following (see attached screenshots for visual example):

Displays the Team Member posts, sorted alphabetically.

You do not need to worry about pagination, but your layout should output up to 12 team members regardless of the site's posts-per-page setting value.

For each team member, display their:

Name (post title)

Photo (featured image)

Position (post meta)

Twitter link (custom post meta)

Facebook link (custom post meta)

Bio (post content)

All Team Member bios should be hidden by default. A link/button should toggle their visibility.


Some additional details:

Cross-browser compatibility is not a requirement for this. You only have 4 hours, we're not expecting perfection on this front.

Taking time to internationalize your text strings is a good habit to follow, if it's not something you do already.

Code cleanliness and inline documentation counts, so pay attention to your formatting, function names, variable names, etc.

Your final deliverable should be either a zip file or a link to a GitHub repository that contains your custom plugin.
