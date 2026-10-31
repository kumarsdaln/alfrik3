<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import {
    LayoutDashboard,
    FolderTree,
    Tags,
    UsersRound,
    ShieldCheck,
    KeyRound,
    UserRoundCog,
    Mic2,
    BookOpen,
    Microscope,
    FileBarChart,
    ClipboardList,
    CalendarDays,
} from '@lucide/vue'

import AppLogo from '@/components/AppLogo.vue'
import NavUser from '@/components/NavUser.vue'
import NavMain from '@/components/NavMain.vue'

import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar'

import type { NavItem } from '@/types'

import { dashboard } from '@/routes'
import { index as users } from '@/actions/App/Http/Controllers/Admin/User/UserController'
import { index as roles } from '@/actions/App/Http/Controllers/Admin/User/RoleController'
import { index as permissions } from '@/actions/App/Http/Controllers/Admin/User/PermissionController'
import { index as interviews } from '@/actions/App/Http/Controllers/Admin/Interview/InterviewController'
import { index as magazines } from '@/actions/App/Http/Controllers/Admin/Magazine/MagazineController'
import { index as research } from '@/actions/App/Http/Controllers/Admin/Research/ResearchController'
import { index as reports } from '@/actions/App/Http/Controllers/Admin/Report/ReportController'
import { index as surveys } from '@/actions/App/Http/Controllers/Admin/Survey/SurveyController'

import { index as categories } from '@/actions/App/Http/Controllers/Admin/Category/CategoryController'
import { index as tags } from '@/actions/App/Http/Controllers/Admin/Tag/TagController'
import { index as participantsRole } from '@/actions/App/Http/Controllers/Admin/ContentParticipant/ParticipantRoleController'




const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutDashboard,
    },
    {
        title: 'Categories',
        href: categories(),
        icon: FolderTree,
    },
    {
        title: 'Tags',
        href: tags(),
        icon: Tags,
    },
    {
        title: 'Participants',
        href: participantsRole(),
        icon: UsersRound,
    },
    {
        title: 'Roles',
        href: roles(),
        icon: ShieldCheck,
    },
    {
        title: 'Permissions',
        href: permissions(),
        icon: KeyRound,
    },
    {
        title: 'Users',
        href: users(),
        icon: UserRoundCog,
    },
    {
        title: 'Interviews',
        href: interviews(),
        icon: Mic2,
    },
    {
        title: 'Magazines',
        href: magazines(),
        icon: BookOpen,
    },
    {
        title: 'Research',
        href: research(),
        icon: Microscope,
    },
    {
        title: 'Reports',
        href: reports(),
        icon: FileBarChart,
    },
    {
        title: 'Surveys',
        href: surveys(),
        icon: ClipboardList,
    },
]
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavUser />
        </SidebarFooter>
    </Sidebar>

    <slot />
</template>