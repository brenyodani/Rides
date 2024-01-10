# Manual Testing for RS4NC Application



## Installing the Application through Nextcloud's Application Store

       Test Case ID: RS4NC_001
###        Description: Install the RS4NC application via Nextcloud's Application Store.
   Test Steps:
            
            - Access Nextcloud's Application Store.
            - Locate RS4NC application.
            - Install the application.
            - Verify successful installation.

### Saving User Login Details

      Test Case ID: RS4NC_002
###        Description: Validate the saving mechanism for user login details.
 Test Steps:

            - Enter user login details.
            - Save the login information.
            - Confirm the saved data's accuracy and security.

### Checking User Input Validations

       Test Case ID: RS4NC_003
###        Description: Perform boundary testing for user input validations.
   Test Steps:

            - Test minimum input lengths.
            - Test maximum input lengths.
            - Validate handling of special characters and boundary values.
            - Ensure error messages for invalid inputs.

### Checking User Settings Validation

       Test Case ID: RS4NC_004
###        Description: Validate user settings input and handling.
 Test Steps:

            - Modify user settings.
            - Verify the correctness of the settings.
            - Check for error handling with invalid settings.

### Checking Login After Adding Login Data

       Test Case ID: RS4NC_005
###       Description: Verify successful login after adding login data.
 Test Steps:

            - Add login data.
            - Attempt login.
            - Confirm successful login.

### Creating, Editing, and Deleting Rides
## Creating Rides


       Test Case ID: RS4NC_006
###        Description: Creating rides and checking file saving locally.
 Test Steps:

            - Add ride details.
            - Attempt saving the ride.
            - Confirm succesfull ride saving.
            - Checking for Bmf, R2G, and local file saving.


       Test Case ID: RS4NC_007
###        Description: Editing rides and checking file saving locally.
 Test Steps:

            - Edit ride details.
            - Attempt saving the ride.
            - Confirm succesfull ride saving.
            - Checking for Bmf, R2G, and local file saving.


       Test Case ID: RS4NC_008
###        Description: Deleting rides and checking file deleting locally.
 Test Steps:

            - Delete ride.
            - Attempt deleting the ride.
            - Confirm succesfull ride deleting.
            - Checking for Bmf, R2G, and local file saving.


       Test Case ID: RS4NC_009
###       Description: Assess application UI across multiple browsers.
 Test Steps:

            - Access the application using Chromium.
            - Access the application using Firefox.
            - Access the application using Safari.
            - Verify UI consistency and responsiveness.

## Unit Tests
### Integration Testing
### Checking Storing Rides as Different Users

 Error Handling

       Test Case ID: RS4NC_010
###        Description: Validate the application's behavior in error scenarios like network failures or invalid responses.

## Performance Testing

       Test Case ID: RS4NC_011
###        Description: Evaluate application response time, load handling, and overall performance under varied conditions.


## Tests made

### The tests were made on 2023.12.18-19

| Test ID     | Description  | Test values  |  Test Outcome |  Expected results   |
| :---        |    :----:    |         :---:|:---:  |        ---:                |
| RS4NC_001   |    Install the RS4NC application via Nextcloud's Application Store. | Search for application in the app store  | The application can be installed through the app store but it is not working after installation | The application gets installed and appears on the top menu bar | 
| RS4NC_002   |    Validate the saving mechanism for user login details. | Saving of login details of bessermitfahren | The application saves the data, but it has 0 response if the saving is succesfull| After the saving of login details a response appears with validation | 
| RS4NC_002   |    Validate the saving mechanism for user login details. | Saving of login details of ride2go | The application saves the data, but it has 0 response if the saving is succesfull | After the saving of login details a response appears with validation | 
| RS4NC_002   |    Validate the saving mechanism for user login details. | Saving of login details of amarillo | The application saves the data, but it has 0 response if the saving is succesfull| After the saving of login details a response appears with validation | 
| RS4NC_002   |    Validate the saving mechanism for user login details. | Saving of login details of R2G + BMF | The application saves the data, but it has 0 response if the saving is succesfull| After the saving of login details a response appears with validation | 
| RS4NC_003   | Perform boundary testing for user input validations. | adding invalid input values and infinit values to the input |The input values can't be empty and can't contain invalid characters and the application shows message about the failures | The application should handle invalid input characters and should check in the input values are empty |
| RS4NC_004   | Validate user settings input and handling.| Adding user settings to the application |The user settings can be changed and it overwrites the previous settings based on the platform  | It should overwrite existing login data and give a response message about saving |
| RS4NC_005   |    Verify successful login after adding login data.| Login with bessermitfahren | The application only gives response on the console | After adding the login data and saving it, a response message should appear with validation or error message|
| RS4NC_005   |    Verify successful login after adding login data.| Login with Ride2Go | The application only gives response on the console | After adding the login data and saving it, a response message should appear with validation or error message|
| RS4NC_006   |    Creating rides and checking file saving locally.| Creating Ride to save only locally | The application saves the ride, redirects to the listing main page and the only response is  that the rides appear in the list | The application should give some response about the validation of file saving | 
| RS4NC_006   |    Creating rides and checking file saving locally.|Creating Ride with Bessermitfahren | The application saves the ride, redirects to the listing main page and the only response is  that the rides appear in the list, the ride is saved on Bessermitfahren | The application should give some response about the validation of file saving | 
| RS4NC_006   |    Creating rides and checking file saving locally.|Creating Ride with Ride2Go |The application saves the ride, redirects to the listing main page and the only response is  that the rides appear in the list, the ride is saved on Ride2Go | The application should give some response about the validation of file saving | 
| RS4NC_006   |    Creating rides and checking file saving locally.| Creating Ride with Ride2Go and BessermitFahren |The application saves the ride, redirects to the listing main page and the only response is  that the rides appear in the list, the ride is saved on Ride2Go and Bessermitfahren | The application should give some response about the validation of file saving | 
| RS4NC_007   | Editing rides and checking file saving locally.| Editing already saved ride that is only saved locally |The application responds only on the console and the file gets edited and saved| The application should give some response about the validation of file saving | 
| RS4NC_007   |    Editing rides and checking file saving locally.|Editing a ride that is already saved on bessermitfahren and locally | The ride gets updated locally and on bessermitfahren, but the application gives response only on console| The application should give some response about the validation of file saving | 
| RS4NC_007   |    Editing rides and checking file saving locally.|Editing a ride that is already saved on ride2go and locally | The ride gets updated both locally and on ride2go but the application only gives response on console| The application should give some response about the validation of file saving | 
| RS4NC_007   |    Editing rides and checking file saving locally.|Editing a ride that is already saved on ride2go and bessermitfahren | The ride gets updated both locally and on ride2go and bessermitfahren but the application only gives response on console| The application should give some response about the validation of file saving | 
| RS4NC_008   |    Deleting rides and checking file deleting locally.|Deleting ride that is saved locally | Trying to delete existing rides that is saved locally | The ride gets deleted from the filesystem and gets deleted from the list and gives a response message with validation | The application deletes the file from the local filesystem but gives only response on the console, the ride gets deleted from the list | 
| RS4NC_008   |    Deleting rides and checking file deleting locally.|Deleting ride that is saved on bessermitfahren |Trying to delete existing rides that is saved locally | The application deletes the file from the local filesystem but gives only response on the console, the ride gets deleted from the list | 
| RS4NC_008   |    Deleting rides and checking file deleting locally.|Deleting ride that is saved on ride2go|Trying to delete existing rides that is saved locally | The application deletes the file from the local filesystem but gives only response on the console, the ride gets deleted from the list | 
| RS4NC_009   | Assess application UI across multiple browsers. |Check application in Chrome browser | The UI shows app and everything works as expected | The UI shows up and works as expected |
| RS4NC_009   | Assess application UI across multiple browsers. |Check application in Firefox browser |The UI shows app and everything works as expected | The UI shows up and works as expected |
| RS4NC_010   |    Validate the application's behavior in error scenarios. | Invalid response data | the application saves the file locally, but not responding if the data cant be stored in the portals | The application should give a message response with the error message |
| RS4NC_011   |    Evaluate application response time, load handling, and overall performance.| Checking the time of storing rides | The application works slower if the portals are checked to store in | The application takes more time to save to the portals because of the web scraping |


