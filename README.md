# POLI TILT
Article Analysis for the politically inebriated.

##Contributors
* Maxwell Renke - maxrenke@gmail.com
* Ryan Bowring - rwb1005@wildcats.unh.edu
* Warren Edgar - wew6@wildcats.unh.edu

##Overview
POLI TILT is a Chrome Web Extension that is powered by the Indico political analysis API to determine the bias of a political news article. Scores include Liberal (left leaning), Moderate (relatively neutral), and Conservative (right leaning). This helpful for those who are "politically inebriated" and want to make sure they are getting the correct perspective of the political landscape. POLI TILT also has a backend MySQL database to store the articles as well as our analysis.

##Features

* Chrome Web Extension (reports bias)
* Web Interface (analyze by URL, or search previous articles)
* Custom tuned algorithm powered by Indico

##Implementation

POLI TILT is powered by the Indico (www.indico.io) text processing API. POLI TILT also uses AlchemyAPI (http://www.alchemyapi.com/) to parse text from articles. POLI TILT is build off of the Microsoft Windows Azure platform (http://azure.microsoft.com) using a Bitnami LAMP stack Ubuntu VM to power an Apache Server and MySQL database.

##How it Works

Simply install (coming soon) our extension and navigate to a news article. Simply click on our extension to find the political "tilt"!

To learn more, follow the link and see other articles that have been analyzed by POLI TILT.

##Try it now

* Download "poli-tilt-extension" and save it to your computer.
* Install Google Chrome
* Settings > More Tools > Extensions > Enable Developer Mode
* Click "Load unpacked extension..."
* You're done!

Alternatively, navigate to http://lampstack5-635795001885558423.cloudapp.net/politilt/tilt.php and enter the URL of an article, or browse articles that have already been analyzed!

##Future Work

A more robust search feature needs to be implemented to make better use of the number of articles analyzed by POLI TILT. Additonal social functionality could also be built in, to share articles with political bias or also recommend articles based on their bias.

##Thank Yous

Big thank you to Indico, Microsoft, and especially HackDartmouth!
