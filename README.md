# James Ibberson - VME Assessment Application 

## Timesheet
- Monday 21st - 1 hour
- Tuesday 22nd - 2 hours
- Wednesday 23rd - 6 hours
- Thursday 24th - 8 hours
- Sunday 17th - 3 hours

## Acceptance Criteria

- **A bulk import to be used once for the initial upload of existing products from a legacy system using the provided .csv file having the following properties: - DONE artisan command, run in database seeder**
- - Name
- - Barcode
- - Brand
- - Price
- - Image URL
- - Date Added  
- **A paginated list of products - DONE**  
- **Search feature - DONE**   
- **Sorting across all fields - DONE**   
- **Filter by price range - DONE**  
- **Filter by brand - DONE**  

- **The ability to mail the filtered product list attached as a .csv to the store staff - DONE**  
- **Create a new product. - DONE**
- - Barcode, name and price are required fields.
- - Unbranded products are from local suppliers.
- - It should be possible to add images.
- **Update a product. You can update either the name, brand, image or price. - DONE**  
- **Delete a product. - DONE** 
- **Notify staff of any product changes. - DONE**

## Future Improvements
Here I'd like to mention a few things which I'm not 100% happy with, that given unlimited (or much more) time, I'd like to correct.

1. When updating a products image, the old image should be deleted. This works on local filesystems, but not s3. 
2. On the create/update product form, there is only a brand select. I would have liked to make it free text, which would match to any existing brand, otherwise a new brand would be created matching the name provided.
3. Some default Laravel branding is still present, would have been cool to find some VME logos or something to replace them

## Running The Application
The application has been developed using Laravel Sail. This is a 1st party package which provides docker containers for a Laravel application.
To run the sail containers, you must have php, composer, docker and docker-compose installed. This is because we need to run `composer install`
on the project to get the Sail package. If you have all 4 prerequisites, and no other docker containers running, then you can use the `install.sh` script provided (you may have to assign execute permission to the script on your own machine).
The docker setup provides a Mailhog container, available at localhost:8025 where you can see emails (attachments found in the "MIME" section when viewing an email).

### File Storage 
I've used s3 for my "images" disk, if you'd like to use the file upload abilities of the application, then create a test s3 bucket and fill in the
`AWS_*` variables in .env with your bucket name, bucket region and appropriate IAM user access key id and secret access key (s3 or s3 compatible service).

