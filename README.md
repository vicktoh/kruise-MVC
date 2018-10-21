# kruise-MVC
A lightweight and simple php library for building MVC web projects
# Main Features
1. Light weight
2. Easily Customisable
3. Simple and Easy to use
4. Implements the model view controller MVC model

# Folder Structure
```bash
├── app
│   ├── core
│   │   ├── Model.php (Base Model class that custom Model classes can extend)
│   |   ├── Controller.php (Base Controller class that custome Controller classes can extend)
│   ├── controllers (Folder for custom controllers)
|   |   ├──Home.php
│   ├── models ( Folder for custom Model)
|   |   ├──HomeModel.php
│   ├── helpers
│   └── vendor (composer vendor folder for additional extensions
|   └── composer.json (composer json file)    
├── css
├── jss
├── .HTACCESS
├── index.php (Entry Point of your App or Website)
```
# Installation
Just clone the directory into your new website or app directory. If your app directory name is 'MY_APP' clone Kruise-MVC into that directory
# Getting Started
Edit the following in the init.php file in the app directory that is app/init.php.
```php
define("SQL_HOST", "localhost");
define("SQL_USER", "YOUR SQL USER");
define("SQL_PASS", "YOUR SQL PASSWORD");
define("SQL_DB", "YOUR SQL DATABASE");
define("ABS_PATH", "http://localhost/MY_APP/"); // or www.mysite.com/ on production server
define("APP_PATH", "C:/xampp/htdocs/MY_APP/"); // Absolute path to the app directory on your local machine or production server
...
...
```
# FrameWork Setup
This framework use the model view controller model
* **Controllers**
  * Controllers as implied contains your site proccess flow and  is basically forms your site urls
  * Urls follow the structure of www.mysite.com/CONTROLLER_NAME/CONTROLLER_METHOD so therefore, www.mysite.com/MyPage/viewPageContent or http://localhost/myapp/MyPage/viewPageContent Will hit the
  controller MyPage.php and the method viewPageContent
  * When accessing your app or site with just the base url 'www.mysite.com' or 'http://localhost/myapp/ Controller Defaults to Home.php and method index;
  * Controller should be created in the app/controllers/ folder
# Example
* Create a Home.php file i.e 'app/controllers/Home.php'
* Create a class that extends the base controller
```php
<?php
class Home extends Controller {
  public function index(){
    // All site Logic Goes Here
    $model = $this->load_model('HomeDb');
    $post = $model->fetchPosts();
    $output_data['post'] = $posts
    $this->load_view('HomeView', $output_data);
  }
}
?>
```
# Model
* Database queries,activities and other site logic and flow should be done in the model
* Models should be created in the 'app/models/' folder E.g 'app/models/HomeDb.php
* Models should extend the base Model Class and always call the Parent constructor
```php
<?php 
class HomeDb extends Model {
    public function __construct(){
      Parent::__construct();
    }
    public function fetchPost(){
      $query = "SELECT * FROM posts";
      $result = $this->query($query);
      return $result;
    
    }
  }
?>
```

