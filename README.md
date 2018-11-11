> ⚠️ This is a Hackathon entry for VandyHacks 5, and was created in under 36 hours. The code in this repo is likely to be hacky and potentially unstable/insecure, so please be careful if forking this repo. [You can view the project's Devpost page here.](https://devpost.com/software/ride-fyi)

# VandyHacks5 Entry: Ride.fyi
## Inspiration
While tools such as Google Maps are a great way to get from A to B, one thing that they neglect to take into account is the cost of travel. With a 39.7 million people in the US living in poverty, and other groups such as students having incredibly tight budgets, we decided to create a webapp to mitigate this problem. Additionally, new bikeshare providers such as Bird and Lime don’t even appear on Google Maps at all, meaning that some of the most cost effective platforms are hidden from view. Ride.fyi aims to combat this by intelligently find hybrid transit options.

## What it does
Ride.fyi's backend is a unified API that connects to as many transport providers as possible, including Uber, Lyft, Bird and local transit networks via HERE.com, and converts their data into a standardised format, ordered by price. These can then be searched via the frontend, which features autocomplete powered by Google Cloud Platform and user geolocation to make things as easy to use as possible. The frontend also handles the display of the converted data, which displays the service/mode of transportation, the price, and the distance of the trip each in a card for the particular surface. Also, in areas where the data is available, we are able to account for gas costs when driving. For rideshare services, we offer one-tap car booking, and for transit, car, walking and cycling, we offer navigation via your device’s native maps application.

Additionally, our API is open to be used by outside services, so if a non-profit wishes to whitelabel our data to help others, they are free to do so. However, we already have a data analytics system API to allow public access of data. According to US news, 52 cities have transit deserts. Our app can help city engineers and activists find these transit dead zones so they can built better infrastructure. For areas with no transit, we provide a customized link to contact your local representative about these issues.

Ride.fyi supports multiple countries and currencies, and adding new services to the platform is quick and easy, even if they don’t officially provide an API. In addition, we have a chrome extension that allows easy linking to our service from other websites. If you right-click an address, you can receive directions to it.


## How we built it
The backend is built in PHP and MySQL, with the frontend being a Bootstrap flavoured collection of HTML, CSS and JavaScript. Analytical data is stored in a MySQL database. Basically, think WordPress’s stack, but ever so slightly less awful.

In terms of APIs, we used many, including the Lyft API, Uber API, HERE.com Geolocation, Transit and Routing APIs and the reverse engineered Bird and Mobike APIs.

## Challenges we ran into
> Bird and Mobike, the two bikeshare services that we support unfortunately don't have public APIs. In order to poll their data, we had to use the undocumented internal APIs which were discovered by reverse engineering their apps. Turns out that the pretending to be an iPhone isn't exactly the most optimal way to consume APIs. Who would have guessed? **-Jack**

> Designing a hybrid system that can find novel transit paths and account for combining different transit systems. Also, any changes to early legs of the journey could affect later scheduled transit options, and the algorithm needs to adapt on the fly. **-David**

> Generating the cards in a seamless and efficient way. Also receiving and implementing the JSON data used within the cards. **-Kedar**

## Accomplishments that I'm proud of
> We’ve managed to build a product that actually works really smoothly and has some great real world applications. As far as I’m aware, there is no existing public API to pull together all the various transport services (especially not including Bird), and certainly none which are able to use rideshare services for partial journeys, so I think we’ve created something genuinely unique and useful. **-Jack**

> My data collection and analysis system. I can’t wait to see how urban areas can leverage the information to improve transit! **-David**

> Contributing to the development of a clean and visually appealing frontend that is intuitive to use and displays the necessary information in a sensible way. **-Kedar**

> One of my main achievements was contributing in the development of this project in general. I have no previous experience of hackathons, or even creating a project of my own. I had to learn several aspects of HTML and JavaScript. to be able to design an optimal front end for our project. **-Carlos**

## What I learned
> I’ve not reverse engineered many APIs in the past, so that was really interesting. Additionally, I’ve never done this much data manipulation in a single project, so getting my head around it was difficult at times. **-Jack**

> Learned how to integrate APIs into a web application . Also how to use bootstrap to make a visually appealing web application. **-Kedar**

> How available transit systems can greatly reflect systemic government issues in addressing the needs of vulnerable, low-income area. On the technical side, I learned about leveraging cURL within PHP, and turning the PHP into an API. **-David**

> Mostly I learned several components of HTML that I’ve never used before and,  how to use them in a way that would work with JavaScript. I also learned what an API is and how to get my mind around then (previously, I’ve never heard what an API was)  **-Carlos**

## What's next for Ride.fyi
> Expand to include many more platforms, and potentially collect the same data from multiple platforms (for example, collect transit data from both HERE.com and Google Maps) in order to validate that it is correct.

> Develop a user base that would allow for deeper analysis of transit options so with enough data we could automatically notify people to contact local representatives about developing transit.
