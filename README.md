## Create a site with the following features:

- Registration page

- Log in page

- Members only dashboard section required after logging in or registering

- The members only page would contain:

  - A list of posts submitted by other users

  - Ability to submit a post (can be as simple as a message)

  - Ability to edit the post, if the authenticated user is the owner

![](public/img/demo1.gif)

# Setup:

- Download the files the go to terminal and cd to `app_symfony` directory

- Run the following commands

`composer install`

`composer dumpautoload -o`

EDIT .env file:

`DATABASE_URL="mysql://root:@127.0.0.1:3306/<DATABASE_NAME>?serverVersion=13&charset=utf8"`

` symfony console doctrine:migration:migrate`
