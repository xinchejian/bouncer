# bouncer
The Xinchejian payment/door/membership system! Allows members to register their (cash) payment and receive a password.
This password can be used to open the door.

The systems workflow
1. Pay money in envelope, write name on it
2. Scan the QR code at the box
3. Fill in name/email and payment
4. Get email with code for door

## SQL
The MySQL DB schema create script can be found in `db.sql`.

## To-Do
* Cronjob for membership reminders
