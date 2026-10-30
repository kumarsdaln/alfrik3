<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import {
    BookOpen,
    BookOpenCheck,
    ClipboardList,
    FolderGit2,
    LayoutGrid,
    MessagesSquare,
    NotebookPen,
    ShieldCheck,
    Tags,
    UserCog,
    Users,
    FileText,
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
import { index as users } from '@/routes/admin/users'
import { index as roles } from '@/routes/admin/roles'
import { index as permissions } from '@/routes/admin/permissions'
import { index as interviews } from '@/routes/admin/interviews'
import { index as magazines } from '@/routes/admin/magazines'
import { index as research } from '@/routes/admin/research'
import { index as reports } from '@/routes/admin/report'
import { index as surveys } from '@/routes/admin/survey'

import {
    index as categories,
} from '@/actions/App/Http/Controllers/Admin/Category/CategoryController'

import {
    index as tags,
} from '@/actions/App/Http/Controllers/Admin/Tag/TagController'

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
    },
    {
        title: 'Categories',
        href: categories(),
        icon: FolderGit2,
    },
    {
        title: 'Tags',
        href: tags(),
        icon: Tags,
    },
    {
        title: 'Roles',
        href: roles(),
        icon: ShieldCheck,
    },
    {
        title: 'Permissions',
        href: permissions(),
        icon: UserCog,
    },
    {
        title: 'Users',
        href: users(),
        icon: Users,
    },
    {
        title: 'Interviews',
        href: interviews(),
        icon: MessagesSquare,
    },
    {
        title: 'Magazines',
        href: magazines(),
        icon: BookOpenCheck,
    },
    {
        title: 'Research',
        href: research(),
        icon: NotebookPen,
    },
    {
        title: 'Reports',
        href: reports(),
        icon: FileText,
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