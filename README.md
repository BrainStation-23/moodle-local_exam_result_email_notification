<p align="center">
  <a href="#">
    <img src="https://user-images.githubusercontent.com/38932580/170247669-adf8b39e-8b55-4aaa-aa1c-a89a4e91d97c.svg" alt="Logo" width="80" height="80">
  </a>
</p>

# Exam result email notification #

It allows you to send Email notification to the Eligible/Disqualified participants after completing a quiz test. It also
emails the Final report of the Quiz to a specific Admin/Teacher/user.

## Prerequisites

- Moodle >= 3.11
- PHP >= 7.3

## Installing via uploaded ZIP file ##

1. Log in to your Moodle site as an admin and go to _Site administration >
   Plugins > Install plugins_.
2. Upload the ZIP file with the plugin code. You should only be prompted to add
   extra details if your plugin type is not automatically detected.
3. Check the plugin validation report and finish the installation.

## Installing manually ##

The plugin can be also installed by putting the contents of this directory to

    {your/moodle/dirroot}/local/exam_result_email_notification

Afterwards, log in to your Moodle site as an admin and go to _Site administration >
Notifications_ to complete the installation.

Alternatively, you can run

    $ php admin/cli/upgrade.php

to complete the installation from the command line.

## Usage

You will get it from Site administration/Plugins/Local plugins

![image](https://user-images.githubusercontent.com/38932580/170028959-89d6d203-1639-47bf-86f7-8ad3a9bc7a5f.png)
![image](https://user-images.githubusercontent.com/38932580/170029128-db8ac860-e556-4e5f-bbe2-c466778254bb.png)

A list of Quiz test would be available to get the Exam email notification.

![image](https://user-images.githubusercontent.com/38932580/170029453-ded6f01c-48d7-4f1a-938f-a185a4e1bce6.png)

Now Press ***+*** Button (Right Column) to make Schedule email for participants as we as admin/teacher/user.

![image](https://user-images.githubusercontent.com/38932580/170031512-6066c73f-5639-488d-8d98-72d04efbf16f.png)

You may customize your email template.

![image](https://user-images.githubusercontent.com/38932580/170032008-27dab567-e0fa-4ddf-b3bc-98cef9092cfa.png)

You may also mention participant name as **{{name}}** into the mail content.

Enable/Disable Email scheduling from Site Administrator

![image](https://user-images.githubusercontent.com/38932580/170033457-f56df256-b4ad-441c-81a8-7565dd1dc8e8.png)
![image](https://user-images.githubusercontent.com/38932580/170033696-6e976f1f-1c19-441d-ac0c-1553894aa9a4.png)

Please don't forget to complete the Setup of **Outgoing mail configuration**

![image](https://user-images.githubusercontent.com/38932580/170034965-ec6bc733-17e2-4399-9dbb-cfe96a839724.png)

<!-- Run Cron Manually -->

## Run Schedule Task (Manually)

```she
php admin/cli/scheduled_task.php --execute=\local_exam_result_email_notification\task\cron_task
```

<!-- CONTRIBUTING -->

## Contributing

Contributions are what makes the open source community such an amazing place to learn, inspire, and create. Any
contributions you make are **greatly appreciated**.

1. Fork the Project
2. Create your Feature Branch (`git checkout -b feature/AmazingFeature`)
3. Commit your Changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the Branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

<!-- Unit Tests -->

## Tests

Add the Code Block below into your phpunit.xml file to execute single test

```sh
    <testsuite name="local_exam_result_email_notification_testsuite">
    <directory suffix="_test.php">local/exam_result_email_notification/tests</directory>
    </testsuite>
```    

And execute the command below to perform test

```sh
php vendor/bin/phpunit --testsuite=local_exam_result_email_notification_testsuite
```

## License ##

2022 @Brain Station 23

This program is free software: you can redistribute it and/or modify it under
the terms of the GNU General Public License as published by the Free Software
Foundation, either version 3 of the License, or (at your option) any later
version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY
WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with
this program. If not, see <https://www.gnu.org/licenses/>.
