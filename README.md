# block-jetpack
An few mu-plugin files to block access to some modules in Jetpack. Props to @r-a-y and @cgrymala

The UMW file allows us to turn off certain modules in Jetpack so they never show regardless of whether you have Jetpack on or not, and lets us control which modules are active network wide, and which are available to toggle on. 

The other two files allow Jetpack to run in Development mode for the entire community without being prompted to connect to Jetpack. Within Settings / General, there is a toggle to turn Development Mode off for users who want to opt into the additional features that wordpress.com offers. There is a check for numbers in the username, which in our case identifies students, and blocks access to the student outright, where age restrictions don't allow students to sign up for a wordpress.com username. 

You could omit this check and use the functionality in an adult context. 
