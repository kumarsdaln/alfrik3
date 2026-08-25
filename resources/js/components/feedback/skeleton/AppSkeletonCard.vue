<template>
    <AppCard class="space-y-4">
        <div class="flex items-center gap-4">
            <AppSkeletonAvatar />

            <div class="flex-1 space-y-2">
                <AppSkeleton width="w-40" />

                <AppSkeleton width="w-24" height="h-3" />
            </div>
        </div>

        <AppSkeletonText :lines="3" />
    </AppCard>
</template>