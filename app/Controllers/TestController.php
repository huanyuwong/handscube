<?php

namespace App\Controllers;

use App\Models\Post;
use App\Models\User;
use Handscube\Assistants\Signature;
use Handscube\Facades\DB;
use Handscube\Facades\Session;
use Handscube\Kernel\Controller;
use Handscube\Kernel\Events\Event;
use Handscube\Kernel\Events\PostComplete;
use Handscube\Kernel\Response;
use Handscube\Kernel\View;

class TestController extends Controller
{
    public function index()
    {

    }

    public function login()
    {
        (new View('home.login'))->show();
    }

    public function testPolicy()
    {
        $user = User::find(1);
        $post = Post::find(3);
        if ($user->can('create', $post)) {
            echo "ture ";
        } else {
            echo "false ";
        }
    }

    public function testView()
    {
        return (new View('home.welcome'))
            ->with([
                'id' => 1,
                'name' => 'testName',
            ]);
    }

    public function testNotifyListener()
    {
        $post = Post::find(1);
        Event::emit((new PostComplete($post)), $this);
    }

    public function testRedirect()
    {
        return $this->redirect('/hello');
        // return $this->back();
    }

    /**
     * DB query builder.
     * Rewrite based on Illumiate\Database.
     * @see https://laravel.com/docs/5.7/queries
     * @return void
     */
    public function testDB()
    {
        return DB::table('posts')
            ->select('id', 'content')
            ->where('id', '<>', 1)
            ->get();
    }

    /**
     * ORM Based on Illumiate\Database. Eloquent ORM.
     * @see https://laravel.com/docs/5.7/eloquent
     * @return void
     */
    public function testORM()
    {

    }

    public function testSession()
    {
        Session::mset(['one' => 'oneValue', 'two' => 'twoValue']);
    }

    public function testResponse()
    {
        return $this->response('Bad gate way', Response::HTTP_BAD_GATEWAY);
    }

    public function testSignature()
    {
        $signature = new Signature();
        $token = $signature
            ->setIss('http://handscube.com')
            ->setAud('testAud')
            ->setId('sign-id-01', true)
            ->set('uid', 'user-0000000001')
            ->expire(time() + 3600)
            ->created(time())
            ->sign($signature::$encrypter['sha256'], 'handscube-secret-key');
        // return $token->getClaims();
        var_dump($token->verify($signature::$encrypter['sha256'], 'wrong-key'));
    }

    public function testQueue()
    {
        for ($i = 0; $i < 10; $i++) {
            if ($this->dispatch(new \App\Tasks\SendMail($i))) {
                echo "$i - dispatch success.\n";
            }
        }
    }

    public function testValidate()
    {
        $data = [
            "author" => [
                "name" => "test-name",
                "description" => "good",
            ],
            "article" => "this is article contents",
        ];

        $this->validate($data, [
            'author.name' => 'required',
            'author.description' => 'required',
            'article' => 'required',
        ]);
    }

    public function greet()
    {
        return 'hello';
    }
}
