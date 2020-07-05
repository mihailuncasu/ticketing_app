//Dashboard Components
import Home from '@/components/app/Home';
import Users from '@/components/app/Users';
import Permissions from '@/components/app/Permissions';
import Roles from '@/components/app/Roles';
import Groups from '@/components/app/Groups';
import Members from '@/components/app/Members';
import GroupChat from '@/components/app/groupchat/GroupChat';
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
            // Auth components
            {
                path: '/',
                component: AuthLayout,
                children: [
                    {
                        path: 'login',
                        name: 'login',
                        component: Login,
                        meta: {
                            middleware: [Middlewares.guest]
                        }
                    },
                    {
                        path: 'verify-email',
                        name: 'verifyEmail',
                        component: VerifyEmail,
                        meta: {
                            middleware: [Middlewares.guest]
                        }
                    },
                    {
                        path: 'reset-password',
                        name: 'resetPassword',
                        component: ResetPassword,
                        meta: {
                            middleware: [Middlewares.guest]
                        }
                    },
                    {
                        path: 'forgot-password',
                        name: 'forgotPassword',
                        component: ForgotPassword,
                        meta: {
                            middleware: [Middlewares.guest]
                        }
                    },
                ]
            },
            {
                path: '/:group_slug',
                component: AppLayout,
                meta: {
                    middleware: [Middlewares.group]
                },
                children: [
                    {
                        name: 'viewUsersDashboard',
                        path: 'view-users-dashboard',
                        component: Users,
                        meta: {
                            middleware: [Middlewares.auth]
                        }
                    },
                    {
                        name: 'viewGroupChatDashboard',
                        path: 'view-group-chat-dashboard',
                        component: GroupChat,
                        meta: {
                            middleware: [Middlewares.auth]
                        }
                    },
                    {
                        name: 'viewMembersDashboard',
                        path: 'view-members-dashboard',
                        component: Members,
                        meta: {
                            middleware: [Middlewares.auth]
                        }
                    },
                    {
                        name: 'viewRolesDashboard',
                        path: 'view-roles-dashboard',
                        component: Roles,
                        meta: {
                            middleware: [Middlewares.auth]
                        }
                    },
                    {
                        name: 'viewPermissionsDashboard',
                        path: 'view-permissions-dashboard',
                        component: Permissions,
                        meta: {
                            middleware: [Middlewares.auth]
                        }
                    },
                    {
                        name: 'viewGroupsDashboard',
                        path: 'view-groups-dashboard',
                        component: Groups,
                        meta: {
                            middleware: [Middlewares.auth]
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
                name: 'notFound',
                component: NotFound
            }
        ]
    }
}

export default routes()
