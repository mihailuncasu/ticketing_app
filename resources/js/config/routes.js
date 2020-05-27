//Dashboard Components
import Home from '@/views/Home.vue';
import Users from '@/views/Users.vue';
import Permissions from '@/views/Permissions.vue';
import Roles from '@/views/Roles.vue';
//import Dashboard from '@/components/Views/Dashboard.vue'
//import Tickets from '@/components/Views/Tickets.vue'

//Auth Components
//import AuthLayout from '@/components/Layouts/AuthLayout.vue'
//import Register from '@/components/Auth/Register.vue'
//import Login from '@/components/Auth/Login.vue'
//import ResetEmail from '@/components/Auth/ResetEmail.vue'
//import ResetPassword from '@/components/Auth/ResetPassword.vue'

//Landing Components
//import Welcome from '@/components/Landing/Welcome.vue'

//General Components
import NotFound from '@/views/NotFound.vue'

//Export routes based on domain used
const host = window.location.host.toUpperCase()

const routes = () => {

    //Test for portal routes
    if (host.includes('SERVICENOW.TEST')) {
        return [
            {
                name: 'Home',
                path: '/dashboard',
                component: Home
            },
            {
                name: 'Users',
                path: '/dashboard/users',
                component: Users
            },
            {
                name: 'Roles',
                path: '/dashboard/roles',
                component: Roles
            },
            {
                name: 'Permissions',
                path: '/dashboard/permissions',
                component: Permissions
            },
            {
                path: '*',
                component: NotFound
            }
        ]
    }
}

export default routes()
