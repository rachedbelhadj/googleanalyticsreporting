# googleanalyticsreporting

## Required

1. Symfony 4.4
1. PHP 7.2

## Bundles

`require google/apiclient:^2.0`

## access Google Account

`GOOGLE_API_KEY=xxxxxxxxxxxxxxxxxxxxxxxxxxx`

`GOOGLE_CLIENT_ID=xxxxxxx-ficldullao12lrbreltaedf87fk1tth1.apps.googleusercontent.com`

`GOOGLE_CLIENT_SECRET=xxxxxxxxxxxxx`

`GOOGLE_CLIENT_REDIRECT_URI=http://localhost:8000/login`

`GOOGLE_CLIENT_SCOPES=https://www.googleapis.com/auth/analytics.readonly`

## Example JSON Rapport 

`{
 message: "Google Analytics reporting",
 profile ID: "215737497",
 rapport: [
             [
                 "Date",
                 "Pages Vues",
                 "Visiteurs",
                 "Visites"
             ],
             [
                 "31/08/2020",
                 0,
                 0,
                 0
             ],
             [
                 "01/09/2020",
                 1,
                 1,
                 1
                 ],
             [
                 "02/09/2020",
                 0,
                 0,
                 0
             ],
 }
 `