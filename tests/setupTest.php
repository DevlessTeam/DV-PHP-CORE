    <?php
//
//    use Illuminate\Foundation\Testing\WithoutMiddleware;
//    use Illuminate\Foundation\Testing\DatabaseMigrations;
//    use Illuminate\Foundation\Testing\DatabaseTransactions;
//
//    class setupTest extends TestCase
//    {
//        /**
//         * A basic test example.
//         *
//         * @return void
//         */
//        public function testSetup()
//        {  
//            $this->visit('/setup')
//                ->type("test@test.com","email")
//                ->type("eddymens","username")
//                ->type("password","password")
//                ->type("password","password_confirmation")
//                ->type("test","app_description")
//                ->type("appName","app_name")
//                ->press('Create App')
//                ->see('Setup successful. Welcome to Devless');
//            
//           $app =  clone $this;
//           return $app;
//
//            
//        }
//        
//        public function testLogin()
//        {
//           $app = $this->testSetup(); 
//           
//           $app->click('Logout')
//            ->type(
//                "password",
//                'password'
//            )
//            ->type(
//                "test@test.com",
//                'email'
//            )
//            ->press('Login')
//            ->see('Welcome Back');
//           
//           $this->afterLogin = $app
//        
//        }
//        
//        
//    }
