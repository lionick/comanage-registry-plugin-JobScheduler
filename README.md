# JobScheduler

JobScheduler Plugin is used to run provisioning actions asynchronously through a cronjob

## Installation

1. Run `git clone https://github.com/rciam/comanage-registry-plugin-JobScheduler.git /path/to/comanage/local/Plugin/JobScheduler`
2. Run `cd /path/to/comanage/app`
3. Run `su -c "Console/clearcache" ${APACHE_USER}` [COManage Reference](https://spaces.at.internet2.edu/display/COmanage/Installing+and+Enabling+Registry+Plugins)
4. Run `Console/cake schema create --file schema.php --path /path/to/comanage/local/Plugin/JobScheduler/Config/Schema`
5. Enable the `Enable Background Job Scheduler` at Co Settings.
6. 🍺

## License

Licensed under the Apache 2.0 license, for details see `LICENSE`
