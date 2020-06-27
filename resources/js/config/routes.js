//Dashboard Components
import Home from '@/components/app/Home';
import Users from '@/components/app/Users';
import Permissions from '@/components/app/Permissions';
import Roles from '@/components/app/Roles';
import Groups from '@/components/app/Groups';
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

//Middlewares;
import Middlewares from "../middlewares/";

//Export routes based on domain used
const host = window.location.host.toUpperCase()

const routes = () => {

    //Test for portal routes
    if (host.includes('APP.WEBSOLUTIONS.TEST')) {
        return [
            // Auth components
            {
                path: '/',
                component: AppLayout,
                children: [
                    {
                        name: 'home',
                        path: '',
                        component: Home,
                        meta: {
                            middleware: [Middlewares.auth]
                        },
                    },
                ]
            },
            {
                path: '/:group_slug',
                component: AppLayout,
                meta: {
                    middleware: [Middlewares.auth]
                },
                children: [
                    {
                        name: 'users',
                        path: '/:group_slug/view-users-dashboard',
                        component: Users,
                        meta: {
                            middleware: [Middlewares.auth]
                        }
                    },
                    {
                        name: 'roles',
                        path: '/:group_slug/view-roles-dashboard',
                        component: Roles,
                        meta: {
                            middleware: [Middlewares.auth]
                        }
                    },
                    {
                        name: 'permissions',
                        path: '/:group_slug/view-permissions-dashboard',
                        component: Permissions,
                        meta: {
                            middleware: [Middlewares.auth]
                        }
                    },
                    {
                        name: 'groups',
                        path: '/:group_slug/view-groups-dashboard',
                        component: Groups,
                        meta: {
                            middleware: [Middlewares.auth]
                        }
                    },
                ]
            },
            {
                path: '/',
                component: AuthLayout,
                children: [
                    {
                        path: '/login',
                        name: 'login',
                        component: Login,
                        meta: {
                            middleware: [Middlewares.guest]
                        }
                    },
                    {
                        path: '/verify-email',
                        name: 'verifyEmail',
                        component: VerifyEmail,
                        meta: {
                            middleware: [Middlewares.guest]
                        }
                    },
                    {
                        path: '/reset-password',
                        name: 'resetPassword',
                        component: ResetPassword,
                        meta: {
                            middleware: [Middlewares.guest]
                        }
                    },
                    {
                        path: '/forgot-password',
                        name: 'forgotPassword',
                        component: ForgotPassword,
                        meta: {
                            middleware: [Middlewares.guest]
                        }
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
                        component: LoginDomain,
                        meta: {
                            middleware: [Middlewares.guest]
                        }
                    },
                    {
                        path: '/register',
                        name: 'register',
                        component: Register,
                        meta: {
                            middleware: [Middlewares.guest]
                        }
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
