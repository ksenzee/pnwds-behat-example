# Automated Visual Regression and Acceptance Tests with Behat

Example configuration to get Behat and Browserstack working with perceptual
diffs. Includes a sample behat.yml and composer.json. From the presentation
http://2014.pnwdrupalsummit.org/pacific-nw-drupal-summit-2014/sessions/deploying-confidence-automated-visual-regression-and
at the 2014 Pacific Northwest Drupal Summit.

### Included projects

This configuration uses Behat 2.4.6. Screenshot comparison happens via a fork
of the excellent
https://github.com/jadu/BehatPerceptualDiffExtension. Browserstack configuration
in FeatureContext.php is adapted from
https://github.com/nulpunkt/behat-mink-browserstack.

## To try it out:

Make sure you have composer installed. If you're new to composer, read the
intro doc at https://getcomposer.org/doc/00-intro.md and follow the installation
instructions. (I recommend following the global installation instructions; you'll
end up using composer for a lot of things, so you might as well have it installed
on your system.)

```
git clone https://github.com/ksenzee/pnwds-behat-example.git
composer update
```
Edit the included behat.yml file to include your browserstack credentials and
target URL under the 'browserstack-screenshots' section of the file. Then run

```
bin/behat --profile=browserstack-screenshots
```

If all goes well, you'll find the test output in this folder saved as
perceptual_diffs.html.

## Potential gotchas

- If you are running your tests against localhost, make sure you have the
'browserstack.debug' parameter set to 'true', and you have a tunnel open
to Browserstack. See http://www.browserstack.com/local-testing for setup
instructions.
- If you are running your tests against a publicly accessible URL, change
the 'browserstack.debug' parameter to 'false'.

## To use ignore masks:

```
bin/behat --profile=browserstack-screenshots
cd pdiffs
cp diff ignore
```

Then edit the image files inside the new ignore directory to be transparent
on any areas of the screen where you do want to see diffs, and black anywhere
you do not want to see diffs (such as an animation or video).

On your next test run, the screenshot comparisons should pass.

## Local screenshots

To use a local instance of Selenium, instead of Browserstack, first download the latest version of
Selenium Server (currently
http://selenium-release.storage.googleapis.com/2.43/selenium-server-standalone-2.43.1.jar).
Then:

```
java -jar selenium-server-standalone-2.43.1.jar &
bin/behat --profile=screenshots
```
