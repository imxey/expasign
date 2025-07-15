import { PlaceholderPattern } from '@/components/ui/placeholder-pattern';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Test Page',
        href: '/test',
    },
];

export default function Test() {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Test Page" />
            <h1>Welcome to the Test Page Nigga</h1>
        </AppLayout>
    );
}
