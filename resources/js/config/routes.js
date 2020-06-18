//Dashboard Components
import Home from '@/components/app/Home';
import Users from '@/components/app/Users';
import Permissions from '@/components/app/Permissions';
import Roles from '@/components/app/Roles';
//import Tickets from '@/components/Views/Tickets'

//Auth Components;
import Login from '@/components/auth/Login'
import VerifyEmail from "@/components/auth/VerifyEmail";
import ResetPassword from "@/components/auth/ResetPassword";
import ForgotPassword from "@/components/auth/ForgotPassword";

//Layout Components;
import AuthLayout from '@/components/layouts/AuthLayout'
import AppLayout from '@/components/layouts/AppLayout'
import LandingLayout from "@/components/layouts/LandingLayout";

//Landing Components
import Register from '@/components/landing/Register'
import Content from '@/components/landing/Content';
import LoginDomain from "@/components/landing/LoginDomain";

//General Components
import NotFound from '@/components/general/NotFound'


//Export routes based on domain used
const host = window.location.host.toUpperCase()

const routes = () => {

    //Test for portal routes
    if (host.includes('APP.WEBSOLUTIONS.TEST')) {
        return [
            {
                path: '/',
                component: AppLayout,
                children: [
                    {
                        name: 'home',
                        path: '/',
                        component: Home
                    },
                    {
                        name: 'users',
                        path: '/admin/users',
                        component: Users
                    },
                    {
                        name: 'roles',
                        path: '/admin/roles',
                        component: Roles
                    },
                    {
                        name: 'permissions',
                        path: '/admin/permissions',
                        component: Permissions
                    },
                    /*{
                        path: 'tickets',
                        name: 'dashbaord.tickets',
                        component: Tickets
                    },*/
                ]
            },
            // Auth components
            {
                path: '/',
                component: AuthLayout,
                children: [
                    {
                        path: '/login',
                        name: 'login',
                        component: Login
                    },
                    {
                        path: '/verify-email',
                        component: VerifyEmail,
                        name: 'verifyEmail'
                    },
                    {
                        path: '/reset-password',
                        name: 'resetPassword',
                        component: ResetPassword
                    },
                    {
                        path: '/forgot-password',
                        name: 'forgotPassword',
                        component: ForgotPassword
                    },
                ]
            },
            {
                path: '*',
                component: NotFound
            }
        ]
    } else {
        return [
            {
                path: '/',
                component: LandingLayout,
                children: [
                    {
                        path: '/',
                        name: 'content',
                        component: Content
                    },
                    {
                        path: '/login-domain',
                        name: 'loginDomain',
                        component: LoginDomain
                    },
                    {
                        path: '/register',
                        name: 'register',
                        component: Register
                    },
                ]
            },
            {
                path: '*',
                component: NotFound
            }
        ]
    }
}

export default routes()
