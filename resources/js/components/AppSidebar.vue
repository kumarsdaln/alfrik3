<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { BookOpen, BookOpenCheck, Cctv, ClipboardList, FolderGit2, LayoutGrid, MessagesSquare, NotebookPen, Users } from '@lucide/vue';
import AppLogo from '@/components/AppLogo.vue';
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import type { NavItem } from '@/types';

import { dashboard } from '@/routes';
import { index as users } from '@/routes/admin/users';
import { index as interviews } from '@/routes/admin/interviews';
import { index as magazines } from '@/routes/admin/magazine';
// import { index as events } from '@/routes/admin/events';
import { index as research } from '@/routes/admin/research';
import { index as reports } from '@/routes/admin/reports';
import { index as surveys } from '@/routes/admin/surveys';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
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
    // {
    //     title: 'Events',
    //     href: events(),
    //     icon: LayoutGrid,
    // },
    {
        title: 'Research',
        href: research(),
        icon: NotebookPen,
    },
    {
        title: 'Reports',
        href: reports(),
        icon: ClipboardList,
    },
    {
        title: 'Surveys',
        href: surveys(),
        icon: Cctv,
    },
];
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
            <!-- <NavFooter :items="footerNavItems" /> -->
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
