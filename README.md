# GHA Project

## Introduction

The GHA Project API offers the possibility to collect some data about some Github Events
This project is composed of 2 core functionnalities:
 1. a command with the CLI that allows us to get all data that matter to us from github events
 2. a public API to expose and filter some data on a **date** and a **keyword**

This project might interest you if you want to do some sentimental analitycs on commit's comment as instance

## Get started
You're just a few steps away from success 🚀

Be sure that PHP 7.4+ is installed along with the sgbd you want
Dont forget to create your own .env.dev with the `DATABASE_URL` if you want to work locally, otherwise, export this to your ENV.
Then, you've got nothing to do except :
1. Clone the repo
2. Go to the project root
3. Run `composer install`
4. Be sure that `DATABASE_URL` is set up in your virtual env or if you work locally in your .env.dev
5. Run `php bin/console doctrine:database:create`
6. Run `php bin/console doctrine:migrations:migrate`

Then you can launch the data import from GithubArchive with the following command :
`php bin/console app:import:gha`
This might take a long moment to import all data. Just be patient 🧘 (I recommand to disable any xdebug / blackfire to avoid ruining perfs and also to set env=PROD)

Once you're done, all GitHub Event of type `PullRequestEvent`, `PushEvent` and `CommitCommentEvent` will be in your database.

Then, you'll be able to run a local server as instance and ask your API with the tool you want.
Just call the following route :
`POST yourhost/api/github-events`

**required params**
```
string  `eventDate` (format: 'Y-m-d')
string  `keyword`
```

## Tests
If you want to run tests, dont forget to create a database where you run migrations. then dont forget to provide a `DATABASE_URL` for the `test` env.
simply run `vendor/bin/phpunit` for that.

Enjoy :)
