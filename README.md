![tko-banner](https://user-images.githubusercontent.com/100104319/232356032-b2abf06b-783a-4ca1-9037-c999afa771a6.png)


### The Plan
* ✅ Create Repository
* ⬜ Get a dockerized and composerized version of site working locally
* ⬜ Remove any unneeded plugins
* ⬜ Redeploy production site with version control and dependency management
* ⬜ Rebuild theme to move off WPBakery Page Builder
* ⬜ Optimize site speed

## Development
### Requirements
* PHP
* Composer
* Docker (and docker-compose)

### Usage
1. Fork the repo.
2. Run `docker build -t wordpress-with-composer .` from the projects root.
3. Run `docker compose up -d` from the projects root.
4. Open web browser to `localhost:8000`
5. Run `docker compose down` to shut down the site.

### Adding plugins
1. Use `composer search` to find the correct package
2. Use `composer require <package/name:#.#.#>` for example `composer require wpackagist-plugin/cache-control:1.4.*`

## Get Involved
### Contribute
There are many ways to help on the project including web design, documentation, bugfixes, new features and more! The best way to get involved is to head over to the [issues](https://github.com/trailkeepersoforegon/tko-website/issues) and see if anything looks interesting. If so add a comment that you are working on the issue and create away. There is no need to be a part of the organization in order to submit a PR.

### Join the Organization
Head over to our [organization page](https://github.com/trailkeepersoforegon) to learn more about what we do. If you want to become more deeply involved reach out to curtis.barnard.jr@trailkeepersoforegon.org.
