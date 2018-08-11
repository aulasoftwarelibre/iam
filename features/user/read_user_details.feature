@user @api
Feature: Show user details
    In order to get all information about an user
    As a sysadmin
    I want to see user details

    Scenario: Get details from an user
        Given an account with username "johndoe" and email "johndoe@mail.com"
        When I check its details
        Then I should see than the username is "johndoe" and the email "johndoe@mail.com"

