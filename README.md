# WEBD 236 To Do app with email password recovery

Goals are:
  - Use a simple API call to send email
  - support password resets securely 

To use:
  - Remix this project
  - Go to [https://www.sendinblue.com/](https://www.sendinblue.com/) and create a free account
  - Login to your account
  - In the user dropdown in the upper right hand corner, choose "SMTP & API"
  - Copy your v3 API key to the clipboard.
  - Open the 🔑.env file in the Glitch editor and paste it (in quotes) as the EMAIL_API_KEY (e.g. EMAIL_API_KEY="your-copied-and-pasted-api-key"
  - Your trial account can send up to 300 emails per day.
  
If you want to send an email to yourself, you'll need to actually make an account in the app with a valid email address and then try recovering your password. You can send 300 email messages per day through the free version of SendInBlue.