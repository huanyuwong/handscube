## 安装

使用composer安装。

```
Composer create-project huanyuanwong/handscube myProject
```

> Handscube运行环境须>=php7.0

## 创建.env文件

如果没有.env文件，将自带的.env.example修改为.env文件，并配置里面的信息，比如配置数据库。

## 使用

使用Handscube自带的开发环境服务器，进入项目根目录运行如下命令:

```
php cube serve
```

> Handscube开发服务器默认运行在localhost的8000端口。

如需改变端口或者使用指定php.ini文件配置可以添加需要的配置项，例如：

```
php cube serve --p 8001 --c '/usr/local/etc/php.ini'
```

> 如果使用自带服务器报数据库相关错误时，请检查相关php文件配置。

### 路由

Handscube的路由配置在根目录`routes/web.php`中。

配置一个Get请求的路由：

```php
Route::get('/', 'index@welcome');
```

该路由会自动解析到`index`模块中`index`控制器中的`welcome`方法。

你也可以为路由命名：

```php
Route::get('/', 'index@welcome')->name('welcome')
```

这样在后续的操作中你可以使用`Route::route('welcome') `来访问它。

#### 带参数的路由

你也可以为路由添加参数，就像这样:

```php
Route::get("/admin/{user}/{option}", "admin.index@user")->name("admin");
```

也可以用冒号代替大括号来表示该处为路由参数：

```php
Route::get('/admin/:user/:option','admin.index@user);
```

该例中带参数的路由将会被自动解析到`admin`模块下的`index`控制器下的`user`方法中。

假设这是你的`IndexController`，你可以这样访问这些请求参数:

```php
	//IndexController.php
    public function user($user, $option){
    	//...
		echo $user . '-' . $option;
	}
```

如果命名路由中含有参数，可以在`Route::route`的第二个参数中指定参数的值：

```php
$url = Route::route('admin','[pars1, pars2]')
```

#### 路由前缀

如果你要访问的多个路由都有同样的前缀，你可以使用`Route::prefix`:

```php
//匹配形如 /admin/connect | /admin/test/1/testname
Route::prefix('/admin', function () {
    Route::get('/test/:id/:name', 'admin.index@test');
    Route::get('/connect', 'admin.index@connect');
});
```

#### 资源路由

如果要使用RESTful风格的资源路由，可以像这样建立一个资源路由:

```php
Route::resource('article', 'article');
```

#### 其它路由使用示例

```php
//注册一个post请求的路由
Route::post('/post','index@put');
//注册一个请求方法为any类型的路由
Route::any('/connect', 'index@connect');
//自定注册请求方法类型的路由
Route::match(["get", "post"], "/testmatch", "index@match");
```

### 控制器

Handscube的控制器默认在`app/controllers`下，`index`模块的控制器就放在`app/controllers`下，其它模块的控制器在相应的文件夹内，比如`admin`模块下`index`控制器在`app/controllers/admin/`目录中。

假设你定义了如下一个路由：

```php
Route::get('/user/{id}','user@show')
```

现在我们在`app/controllers`创建一个`UserController`

```php

namespace App\Controllers;

use App\User;
use Handscube\Kernel\Controller;

class UserController extends Controller
{
    /**
     * 展示用户信息
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
		return User::find($id);
    }
}
```

#### 依赖注入（IoC）

当我们访问控制器方法时，Handscube内核帮我们完成了依赖注入（或称控制反转）功能，这意味着只要我们给出参数提示，就可以很轻松访问到该参数的注入实例。

同上的场景：

```php

namespace App\Controllers;

use Handscube\Kernel\Request;
//...
public function show(Reqeust $request, $id){
    if($request->input){
        //逻辑代码
    }
}
```

在以上示例中，我们完成了`Reqeust`核心类的依赖注入，方便我们访问`request`类上的属性和方法，而这些过程对用户来说是透明的。

#### 模型绑定

有时候我们想将直接将路由接受的相关参数直接转换为相应的模型，这在Handscube中也是可以轻松做到的。

在Handscube中，模型绑定分为隐式绑定和显式绑定。

##### 隐式绑定

在隐式绑定中，如果路由接受的参数是需要转化成相应模型的时候，我们可以直接给出模型参数的提示，Handscube会自动帮我们完成相应的模型注入。

假设你有一个路由是这样的：

```php
Route::get('/test/:user/:post','index@test')
```

如果我们不进行任何隐式绑定的操作，在控制器`test`方法中会直接接受到`id`的值

```php
public function test(Request $request, $user, $post){
    // $user和$post是相应的id值
}
```

如果我们想直接获取`id`值对应的模型，我们可以给它进行类型提示：

```php
    
namespace App\Controllers;

use App\Models\Post;
use App\Models\User;
use Handscube\Kernel\Controller;
use Handscube\Kernel\Request;

class IndexController extends Controller
{
    public function test( Request $request, User $user, Post $post)
    {
        //此时$user和$post已经被绑定为相应的数据模型了。
        return $this->response($user->id . '-' . $post->id);
    }
```

##### 显示绑定

有时候我们想固定一个模型不变，这时候我们可以使用显示绑定，使用显示绑定的方法有两种，一种是在`控制器`中的`model`方法中声明，另一种是在`控制器守卫`中声明`[后文将介绍]`。

在控制器中显性绑定一个模型:

```php
class IndexController extends Controller
{
    public static function model(){
        //显式绑定user为id为1的数据模型
        Route::bind('user',function($value){
            return User::find(1);
        });
    }
    
    public function test( Request $request, User $user, Post $post)
    {
        //显示绑定user后，无论传入id的值如何改变,这里的user模型将一直是id为1的user模型
        return $this->response($user->id . '-' . $post->id);
    }
```

#### 资源控制器

资源控制器是带有RESETful风格的控制器，使用该控制器须先定义资源路由:

```php
Route::resource('resetful','articles');
```

定义路由后系统会注册一系列相关路由信息在路由系统中，具体如下:

| 请求类型  | URI                      | 动作    | 路由名称         |
| --------- | ------------------------ | ------- | ---------------- |
| GET       | /articles                | Index   | articles.index   |
| GET       | /articles                | Create  | articles.create  |
| POST      | /articles                | store   | articles.store   |
| GET       | /articles/{article}      | show    | articles.show    |
| GET       | /articles/{article}/edit | edit    | articles.edit    |
| PUT/PATCH | /articles/{article}      | update  | articles.update  |
| DELETE    | /articles/{article}      | destory | articles.destory |

资源控制器方法较多，写起来比较麻烦，你可以通过以下命令快速生成资源控制器。

```php
php cube create:controller ArticlesController --resource
```

该命令会在`app\controllers`下生成一个名为`ArticlesController`的资源控制器。

同样如果想生成普通控制器，运行如下命令：

```php
php cube create:controller NormalController
```

该命令在`app\controllers`下生成了一个名为`NormalController`的普通控制器。

#### 重定向

有时候我们需要在控制器中重定向一个请求，可以这样使用：

```php
$this->redirect('https://baidu.com');
```

如果我们想在控制器验证失败后返回上一步，可以在控制器内使用`back`函数

```php
$this->back();
```

---



### 请求

#### 注入请求

你可以在控制器中通过依赖注入的方式获取HTTP请求的实例，要获取该实例，你应该在要访问的控制器方法中进行`Handsucbe\Kernel\Request`的类型提示，这样传入的实例会通过内核自动注入:

```php

namespace App\Controllers;

use Handscube\Kernel\Request;

class UserController extends Controller {
    
    public function store(Request $request){
        //...
    }
}
```

#### 路由参数

如果控制器方法想获取路由参数的数据，比如你有这样一个路由：

```php
Route::get('user/{id}','user@show');
```

那么在相应控制器方法中可以直接获取该路由参数:

```php
//UserController.php

public function show(Request $request, $id){
    //...
}
```

#### 匿名函数获取参数[已废弃]

Handsucbe本来定义了匿名函数控制器用于接受路由参数或操作方法，但匿名函数和php语言风格格格不入，不像Javascript语言特性那样对闭包的支持那样完整，并且在php实际使用中用匿名函数来充当控制器本身不太现实，所以Handscube直接摒弃了这种功能。

#### 获取请求参数

使用IoC我们可以很方便的在控制器中获取请求参数:

```php

namespace App\Controllers;
use Hansubce\Kernel\Request;

class PostController exnteds Controller {
    //...
    public function store(Request $request){
        //$request->post 映射了$_POST超全局变量
        $id = $request->post['id'];
    }
}
```

以上示例中`$request->post`其实就是`$_POST`中的数据，相似映射还有：

```php
$request->query //映射了$_GET查询参数
$request->request //映射了$_REQUEST超全局变量
$request->input //映射并解析了php标准输入参数[解析为数组]
$request->fiels //映射$_FILES.
$request->_server //对应$_SERVER超全局变量
```

以上这是`request`属性都是可以在控制器直接访问的。

如果你不想获取解析后的php标准输入的参数，可以访问`$request->raw_data`属性。

>注意：若接受的原生数据是二进制图片信息，Handscube会先将其用base64编码再存入raw_data。

有时候我们不想关注获取的数据是来自什么请求方式提交，只想获取客户端传入的数据，可以使用`$request->input`方法，比如有这样一个路由:

```php
Route::put('post/{id}','post@update')
```

在`PostController`控制器中，我们可以这样获取请求的`id`参数:

```php

namespace App\Controllers;

class PostController extends Controller {
    
    //...
    public function update(Request $request,$id){
        //也可以直接输出参数上的$id值，效果是相同的。
        return $request->input('id');
    }
}
```

如果不给`input`方法传参数，将获取所有来自前端传入的数据 [包括pathInfo的数据]:

```php
return $request->input();
```

> ​	pathInfo数据是包含在uri路径上的参数。比如/uri/{param}，这里的param就是pathInfo数据。

如果我们要获取自定义的参数，`input`函数也接受一个数组：

```php
//传入id和name，获取形如 [1,'value'] 的数据
$result = $request->input(['id','name'])
```

如果我们只想获取其中的一个或多个参数，可以使用`only`函数

```php
$request->only('id'); //只获取id参数
$request->only('id','uid','name'); //获取id、uid、name参数
```

有时候我们想获取除了某个或多个数据之外的其它所有数据，可以使用`except`函数:

```php
$request->except('id'); //获取除了id之外的其它所有数据
$request->except('id','uid'); //获取除了id和uid之外的其它所有数据
```

---



### Cookie

在控制器中可以使用`Handscuce\Assistants\Cookie `类访问`cookie`

```php
\\IndexController.php
    
use Handscube\Assistants\Cookie;

public function testSetCookie(){
	//设置cookie
    Cookie::set($name,$value,$expire);
    //以下操作效果相同。
    (new Cookie)
    		->name($name)
        	->value($value)
         	->expire($expire)
            ->path($path)
            ->domain($domain)
            ->secure($secure)
            ->httpOnly($httpOnly);
    		->save();
}
//读取cookie
public function testGetCookie(){
    Cookie::get('name','value');
}
```

> Cookie的设置都是经过加密的，在访问Cookie时将自动解密，加密使用.env的APP_KEY作为密钥。

---



### Session

Handscube设置了部分像Laravel一样的门面概念，Session就包含其中。因此我们可以使用门面来访问Session。

```php

use Handscube\Facades\Session;

//Controller
Public function testSession(){
    Session::get('key');//通过门面访问session
    Session::set('key','value');//设置session的值
    //设置多个session的值
    Session::mset([ 
        'key1'=>'value1',
        'key2'=>'value2'
    ]);
}
```

获取所有`Session`的值可以使用`Session::all()`方法，当我们使用Session门面访问方法时，Handscube会自动注入相应的实例。这样我们就可以像访问静态方法一样访问普通方法了。

#### Session驱动

Handscube默认使用`file`方式作为session驱动，如果你想修改session的驱动方式，可以在`.env`或者在`app\configs\App.php`中进行配置。目前Handscube除了`file`方式外公支持两种驱动方式，一种是`redis`另一种是`mysql`，如果使用`mysql`作为session驱动的方式，应该建立类似如下的session表:

| Field  | Type         | Comment    |
| ------ | ------------ | ---------- |
| id     | varchar(255) | Session id |
| expire | datetime     | 过期时间   |
| data   | text         | 数据       |

> 你可以在配置文件中修改session_driver_table指定session驱动的表名。

### 守卫和检查站

Handscube内置了守卫概念，用以在减少用户配置的前提下保证数据的过滤。

#### 应用守卫（全局守卫）

应用守卫是整个应用的守卫，所有经过应用的数据都必须经过该守卫的过滤。

你可以在`app\kernel\App.php`中的`bindGuard`方法内绑定应用守卫：

```php

namespace App\Kernel;
use App\Kernel\AppGuard;
use Handscube\Kernel\Application;

class App extends Application
{
    //绑定应用守卫
    public function bindGuard()
    {
        return AppGuard::class;
    }
}
```

守卫绑定后，我们就可以使用守卫了，以上示例的应用守卫在App\kernel空间下：

```php

namespace App\Kernel;
use Handscube\Kernel\Guard;

class AppGuard extends Guard
{

    const cate = "app";

    protected $register = [
		//在这里注册检查站
    ];
}
```

以上展示了一个应用守卫的结构，在`$register`属性中我们可以注册检查站。

#### 检查站

检查站和守卫是紧密相连的，一个守卫可以包含多个检查站，你可以理解成一名安检人员带你通过多个安检设备保证安全后才通行的概念。

同样，一个守卫注册一个或多个检查站后，数据必须依次进入检查站进行检查，只要在全部通过后，数据才会进入下一个处理单元。

要注册一个检查站，可以在守卫的`$register`属性中这样操作:

```php
 protected $register = [
		OneStation::class,
		TwoStation::class
    ];
```

这样就在守卫中注册了两个检查站，每一个检查站都有一个`handle`方法，用于接受要检验的数据，我们假设在`OneStation`检查站用于检查传入的`api token`是否有效，我们可以在`OneStation`中这样定义：

```php

namespace App\Stations;
use Handscube\Kernel\CrossGate;
use Handscube\Kernel\Response;
use Handscube\Kernel\Station;

class OneStation extends Station
{
    public function handle(\Handscube\Kernel\Request $request)
    {
        //检查接口token是否有效。
        if (CrossGate::verifyAccessToken($request->header['Access-Token'], $request->input()) === true) {
            return;
        } else {
            //如果无效返回401 Unauthorized.
            (new Response())->withJson(['status' => Response::HTTP_UNAUTHORIZED, 'message' => Response::$statusTexts[Response::HTTP_UNAUTHORIZED]])->send();
        }
    }
}

```

> 在以上示例中我们定义了一个可以检验接口Token的检查站用于筛选请求是否合法。在实际使用中，我们完全可以注册多个检查站完成多项功能的检查。

#### 控制器守卫

`控制器守卫`相比`应用守卫`有些不同，你可以完全不用进行任何所谓中间件的注册和文件配置来过滤数据，

每一个控制器对应一个守卫，比如`IndexController`的守卫名为`IndexGuard`，Handscube默认将控制器守卫的目录放在了`app\guards`下，一个典型的控制器守卫形如以下：

```php

namespace App\Guards;

use Handscube\Kernel\Guards\ControllerGuard;
use Handscube\Kernel\Request;

class IndexGuard extends ControllerGuard
{
    /**
    * 注册一个或多个对该控制器所有方法有效的检查站[除了except指定的方法]
    */
    protected $register = [

    ];
    
    /**
    * $register注册的检查站对该数组注册的方法无效
    */
    protected $except = [
		'login'
    ];

    /**
    * $register属性注册的方法只对以下方法有效
    */
    protected $only = [
		'user'
    ];

    /**
    * 给一个控制器方法具体指定一个检查站
    */
    protected $specified = [
        // "connect" => ChangeIdStation::class,
    ];

    /**
    * 与IndexController中的index方法对应，
    * 数据在进入控制器的index方法前须先通过此方法检验。
    */
    public function indexGuard(Request $request)
    {
        if($request->id){
            //...做一些检查id的操作。
        }
    }

    //对应IndexController中login方法
    public function loginGuard()
    {
		//...
    }
}

```

在通过控制器守卫注册的检查站后，数据还会通过相应的`方法守卫`(比如`login`方法的方法守卫是`loginGuard`)做最后的检验，如果检查通过，路由系统才会将数据分配到相应的控制器中。

如果某个控制器方法没有注册方法守卫（比如一个`IndexController`中有一个`connect` 方法，其控制器守卫`IndexGuard`中没有`connectGuard`）那么该方法守卫将被忽略。

同样如果一个控制器没有控制器守卫，那么该控制器守卫将被忽略。

##### 控制器守卫模型绑定

在控制器守卫中，你也可以定义一个model方法来绑定请求参数到模型，如在IndexGuard中:

```php
//IndexGuard.php
public function model(){
    parent::model();
    Route::bind('user',function($vakue){
        return User::find(1); //绑定请求参数user到id为1的User模型
    })；
    Route::bind('anotherParam',function($value){
            //...
    })
}
```

这样如果在`IndexController`进行类型提示获取的模型中将只能访问到被绑定的模型:

```php
//IndexController.php

public function storeUser(User $user){
    return $user->id; //无论请求参数如何改变，值都为1
}
```

#### 注册控制器守卫位置

1. Index模块的控制器守卫须注册在`app\guards`根目录中。

2. 其它模块（比如`Admin`模块）的控制器守卫注册在相应模块中（此例在`app\guards\admin\`目录下）。

---



### 组件

#### 普通组件

Hanscube将可以直接通过app访问的内置对象称为组件，注册组件在`app\configs\App.php`下：

```php
return [
    "components" => [
        "register" => [
            "router" => Handscube\Kernel\Route::class,
            "dev" => Handscube\Dev\DevComponent::class,
        ],
        "defer" => [
            \Handscube\Assistants\Arr::class,
        ],
    ],
]
```

上例中，在`Components`选项下的`Register`选项值就是注册的组件，注册后的组件可以在控制器中通过`app`容器直接访问：

```php

    //IndexController.php
    public function user(){
    	//通过request组件获取值
    	return $this->app->request->input('user_id');
	}
```

在以上示例中可以看到，我们既可以通过依赖注入访问request数据，也可以通过组件式访问，这取决于个人喜好。

#### 延迟加载组件

如果我们在应用中注册过多的组件，会将应用变得臃肿，影响性能。这时我们可以给应用注册一些异步组件，在配置文件`app\configs\App.php`的`components`选项中配置如下：

```php
 "defer" => [ App\Components\DeferComponent::class],
```

该配置会注册一个名为DeferComponent的异步组件，只有某些需要的情况通过app容器访问的时候才会真正的进行加载。

---



### 事件

有时候我们希望在昨晚一件事后触发一个事件，Handscube事件系统为我们提供了该项功能，在Hanscube中，一个事件触发后会被派发到`事件调度器`，调度器会根据各种情况调度不同的事件到正确的位置中。

#### 新建事件

我们可以在`app\events`下新建一个`UserStore`事件在用户存储成功后触发，因此我们需要事件接受一个`User`的ORM数据模型，像这样：

```php
namespace App\Events;

use App\Models\User;
use Handscube\Kernel\Events\Event;

class UserStore extends Event
{

    public $user; //用户模型

    public function __construct(User $user)
    {
        parent::__construct($user); //须调用父类构造方法完成一些初始化工作。
        $this->user = $user;
    }
}

```

这样就新建了一个名为`UserStore`的事件。

#### 事件监听器

##### 注册事件和监听器

Handscube在`app\suppliers`中提供了调度器供应商用以注册事件和监听器。在该目录下`ScheduleSupplier.php`这样注册：

```php

namespace App\Suppliers;

use Handscube\Kernel\Events\PostComplete;
use Handscube\Kernel\Listeners\AdminNotifination;
use Handscube\Kernel\Listeners\PostNotifination;

class ScheduleSupplier
{
    /**
    * 注册事件监听器
    */
    public static $listeners = [
        PostComplete::class => [
            PostNotifination::class,
            AdminNotifination::class,
        ],
        'App\Events\UserStore' => AdminNotifination::class,
    ];
	//注册事件订阅者
    public static $subscribers = [
        \App\Listeners\StoreEventSubscriber::class,
    ];

    public static $observers = [

    ];
}

```

在以上示例的`$listeners`属性中注册了一个名为`PostCompolete`的事件和两个事件监听器`PostNotifination`和`AdminNotifination`。

##### 动态注册事件

Handscube也提供了动态注册事件的方法，只需要访问`Handscube\Kernel\Events`中的`listen`方法就可以进行注册了：

```php
//或者使用Event::on()方法。
Event::listen(
            '\Handscube\Kernel\Events\PostComplete',
            'App\Listeners\StoreEventSubscriber@onPostStore'
           );

```

##### 编写事件监听器

事件监听器存放在`app\listeners`目录下，这里我们假设编写一个`PostNotifination`事件:

```php
namespace App\Listeners;

use Handscube\Kernel\Events\Event;

class PostNotifination extends Listener
{
    //每一个事件监听器有一个hanlde方法用来接受事件对象
    public function handle(Event $event)
    {
        if($event->post->title == 'Welcome'){
            //模拟完成了一个任务
            echo "PostNotfination listener trigger.\n";
        }
    }
}

```

这样，相关事件触发后，会自动将事件本事注入到这个事件监听器中，我们就可以很方便的进行某些逻辑操作了。

#### 事件订阅者

假如我们希望注册多个事件的多个监听器，我们可以使用Handscube的事件订阅者。

##### 编写事件订阅者

事件订阅者的`subscribe`方法可以绑定事件到相应的监听方法，你可以像这样新建一个事件订阅者。

```php
namespace App\Listeners;

use App\Events\UserStore; //事件

class StoreEventSubscriber
{

    //事件订阅方法
    public function onUserStore($event)
    {
        echo "> User $event->trigger Store Listener trigger.\n";
    }
	//事件订阅方法
    public function onUserLogin($event)
    {
        //访问$event数据的代码
        //...
        echo "> User Login Listener trigger.\n";
    }
	//事件订阅方法
    public function onPostStore($event)
    {
        echo ">> Post Store Listener trigger.\n";
    }
	//订阅了两个事件PostComplete、UserStore和相关的监听方法
    
    public function subscribe($event)
    {
        $event->on(
            '\Handscube\Kernel\Events\PostComplete',
            //绑定到onPostStore方法
            'App\Listeners\StoreEventSubscriber@onPostStore' 
        );
        $event->listen(
            UserStore::class,
            [
                //绑定了多个监听方法
                'App\Listeners\StoreEventSubscriber@onUserLogin',
                'App\Listeners\StoreEventSubscriber@onUserStore',
            ]
        );
    }
}

```

##### 注册事件订阅者

要注册一个事件订阅者，需要在`app\suppliers\ScheduleSupplier.php`中进行：

```php
class ScheduleSupplier
{
    //注册一个事件订阅者
    public static $subscribers = [
        \App\Listeners\StoreEventSubscriber::class,
    ];
}
```

事件订阅者和事件监听器一样都应该放在`app\listeners`下。



#### 事件触发

假设我们在控制器完成了一个存储文章的动作，此时我们可以触发一个PostCompolete事件：

```php
//PostController.php

public function store(Post $post){
	if(storePostDone($post)){
        //该文章存储完成后，触发PostCompolete事件，
        //$post作为数据传给入，第二个参数$this表示由谁触发。
        Event::emit(new PostComplete($post)), $this);
	}
}
```

---



### 数据库

####数据库配置

数据库的配置在`.env`和`app\configs\database.php`中，一个典型的数据库配置如下：

```php
return [
    //mysql配置
    "mysql" => [
        'driver' => 'mysql',
        'host' => 'localhost',
        'port' => '3306',
        'database' => 'testdb',
        'username' => 'root',
        'password' => 'rootpsd',
        'unix_socket' => '',
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix' => '',
        'strict' => true,
        'engine' => null,
    ],
	//redis配置
    "redis" => [
        "parameters" => [
            'scheme' => 'tcp',
            'host' => '127.0.0.1',
            'port' => 6379,
            'database' => 0,
        ],
        "options" => [

        ],
    ],
];
```

Handscube目前只支持`Mysql`和`Redis`两种数据库。

#### ORM

要编写一个数据模型，可以`app\models`下，假如你有一个表叫`posts`，你可以创建一个Post模型:

```php
namespace App\Models;

use App\Kernel\Model;

class Post extends Model
{
	protected $table;
}

```

然后就可以在控制器访问了:

```php
public function show($id){
    return Post::find($id);
}
```

具体使用参照[Laravel Eloquent ORM](https://laravel.com/docs/5.7/eloquent).

#### 查询构建器

有时候我们因各种原因不想使用ORM访问数据库，我们可以使用查询构建起。Handscube重写了查询构建器，其使用方法和Laravel相同。

使用查询构建器访问数据库:

```php
 return DB::table('posts')
            ->select('id', 'content')
            ->where('id', '<>', 1)
            ->get();
```

该查询构建器访问了数据库posts表中id不为1的数据。

更多使用示例请参照[Laravel查询构建器](https://laravel.com/docs/5.7/queries)。

#### Redis操作

Handscube的`redis`数据操作依赖了`predis`包，我们假设你已经安装了这个Composer包。

Handscube的redis操作方法和predis一样，具体的使用可以访问predis官网，本文不再赘述。

为了访问更加方便，当你在redis配置文件正确后，可以使用Handscbue提供的`Redis门面`操作Redis数据库。比如：

```php
$value = Redis::get('key');
```

---



### 权限设置

#### 模型动作策略

很多时候我们需要对某个用户的权限进行一个限制，比如哪些文章是用户能更新的，哪些是能删除的。

我们可以为某个模型角色新建一条`动作策略`用以判断该用户是否有权限进行某个资源的操作。

比如，我们想判断一个用户角色是否有权限更新某个文章模型的资源，我们可以在`User`模型中

新建这样一条策略：

```php
namespace App\Models;

use App\Kernel\Model;

class User extends Model
{
    protected $table = "user";

    public function updatePolicy(User $user, Post $post)
    {
        //只有当文章属于该User模型时才能进行更新。
        return $user->id === $post->user_id;
    }
```

> 策略应遵照动作(action) + 'Policy'的命名方式。

在以上示例中，`User`模型新建了一条`Update`策略，该策略传入`User`模型和`Post`模型两个参数，用以判断该用户是否有权限更新某条文章资源。

#### 使用模型动作策略

要在控制中的逻辑代码内使用模型动作策略，须使用模型上的`can`方法，该方法会根据你定义的模型动作策略判断该用户是否有权限更新指定资源。

```php
class UserController {

    public function update(Post $post){
        //假设你有一个判断当前用户的方法currentUser()
        if($this->currentUser()->can('update',$post)){
            //更新资源操作...
        }else{
            //无权更新资源，返回错误信息。
        }
    } 
}
```

> 注意：如果没有定义模型的动作策略，Handscube将调用默认的策略进行权限判断，默认的策略就是用户id必须和资源id一致。

#### 控制器方法权限

有时候你想设置某个用户是否有访问某个控制器方法的权限，比如你想设置只有登录用户才可以访问某个控制器方法，`Handscube`并没有像`Yii`那样专门为此定义了一系列的支持来操作，Handsubce建立之初就时想轻量级简单和高度自定义化，所以你完全可以使用自定义`控制器守卫`和`检查站`来达到同样的效果。

---



### 队列

Handscube现阶段只实现了一个基本的队列demo，暂时不能完整的实现成熟队列功能。

要启动极简单的队列demo可以在命令行输入：

```
php cube start:worker
```

要派发任务可以在控制器中派发，Handsubce内部只定义了一个测试用的`Task`类:

```php
//IndexController.php
public function testQueue(){
    //生产10个队列任务到队列。
    for ($i = 0; $i < 10; $i++) {
            if ($this->dispatch(new \App\Tasks\SendMail($i))) {
                echo "$i - dispatch success.\n";
            }
        }
}
```

任务生成后会在后台被消耗，这个测试demo使用的是redis来作为队列驱动。

> 专业的消息队列并不是一件小和简单的项目，如果Handscube未来要重写，会考虑跟更专业的消息队列做好平滑的兼容工作，使之使用起来更像是在调用本身的一个功能模块，而不是自己去从零实现。

---



### 响应

要在控制器中返回一个响应很容易，你可以这样发出一个HTTP响应。

```php
use Handscube\Kernel\Response;

class IndexController {
    
    public function store(User $user){
        if($this->storeUserDone($user)){
            /** 
            * 这样就返回了一个200状态码的响应，
            * success'是响应的内容，默认为空字符串
            */
            (new Response("success",Response::HTTP_OK))->send();
            }
        }
}
```

如果你觉得每次调用send()方法比较麻烦，你可以直接return一个响应：

```php
return new Response("success",HTTP_OK))
```

你也可以在创建Response示例后在设置相关参数：

```php
return (new Response())
		->setContent('Hello Handsubce.')
		->setStatusCode(Response::HTTP_OK);
```



#### 设置响应头信息

设置头信息有两种方法，一种是在创建时设置：

```php
$response = new Response(
    'Content',
    Response::HTTP_OK,
    [
        'content-type' => 'text/html'
    ]
);
return $response;
```

还有一种是在创建Response示例后设置:

```php
$response->headers->set('Content-Type', 'text/plain');
```

---



#### 以JSON格式返回

JSON在数据交换中十分常用，如果我们希望将响应以Json数据返回，可以调用响应的`withJson`方法，

该方法接受要返回的数组数据:

```php
use Handsucbe\Kernel\Response;

public function returnWithJson(){
    (new Response())->withJson(['status'=>'success','code'=>200])->send();
}
```

如果你觉得每次new一个响应在写法上有点麻烦，你可以这样使用达到童谣的效果：

```php
return $this->response()->withJson($dataArray);
```

> 注意：Handscube没有提供形如以下的全局函数，是因为该编程习惯太过于 ”脚本化“，看起来来路不明，不利于我们的对象化模块化编程习惯，所以坚持少用全局函数有利于我们良好的编程习惯。

```php
response($data)->send();
```



#### 设置Cookie

前面已经介绍了设置Cookie的一种方法，这里你也可以这样在响应附带Cookie

```php
//在某个控制器中
public function returnWithCookie(){
	$cookie = (new Cookie())
				->name("key")
				->value("value");
    return $this->response()->withCookie($cookie) //withCookie接受一个Cookie实例
}
```



#### 设置Http协商缓存

如果要设置HTTP缓存可以使用`setCache`方法：

```php
$response->setCache(array(
    'etag'          => 'E-tagabc',
    'last_modified' => gmdate('D, d M Y H:i:s',time())." GMT"
    'max_age'       => 3600,
    'private'       => false,
    'public'        => true,
));
```



#### 重定向

该重定向和控制器的`$this->redirct()`类似。

```php
(new Response())->redirect($targetUrl)
```



### 视图

#### 展现一个视图

要在控制中直接展现一个页面，可以实例化一个`Handscube\Kernel\View`对象：

```php
use Handsubce\Kernel\View;

class TestController extends Controller
{
    public function login()
    {
        //展现home模块下的Login.php视图。
        (new View('home.login'))->show();
    }
```

此时我们在项目根目录`\resources\views\home`下创建一个Login.php的文件，就可以展现视图了。



#### 为视图分配数据

如果我们要为某个视图提供数据，需要使用`Handscube\Kernel\View`对象的`with`方法:

```php
public function showWelcomde()
    {
        return (new View('home.welcome'))
            ->with([
                'id' => 1,
                'name' => 'testName',
            ]);
    }
```

以上示例为`home`模块下的`welcome`视图文件分配了一个数组数据。

在视图文件中我们当然可以使用`<?php ?>`的原始方式访问数据，也可以使用`{$variable}`大括号包裹的方式访问变量数据，Handscube将自动解析并为相应位置注入该变量的值：

```php+HTML
<!--- Welcome.php --->
<div>
    <!--- 使用大括号访问变量 --->
    <p>{$id}</p> 
    <p>{$name}</p>
</div>
```

> Handscube当然没有过多提供模版引擎的功能，目前前端发展迅猛，建议使用专业的前端处理数据。

如果没有提供视图模块，将使用Home模块作为视图的默认模块。

---



### 其它

#### JWT

Handscube提供了一个JWT签发器，用于前后端分离的情况，你可以像这样使用它们：

```php
public function signToken(){
 		$signature = new Signature();
        $token = $signature
            ->setIss('http://handscube.com')
            ->setAud('testAud')
            ->setId('sign-id-01', true)
            ->set('uid', 'user-0000000001')
            ->expire(time() + 3600)
            ->created(time())
            ->sign($signature::$encrypter['sha256'], 'handscube-secret-key');
    	$data = $token->getClaims(); //获取数据
    	//验证token是否有效。
    	var_dump($token->verify($signature::$encrypter['sha256'], 'wrong-key'));
}
```

---



#### 说明

>！Handscube只是一个在短暂时间内随意打造的用于仿照框架功能的小项目，
>
>​    现阶段只可用于交流参考之用，不能用于生产环境。

