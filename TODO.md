# Separate Dashboards for Admin, Doctor, and Patients

## Tasks

-   [x] Create Admin Dashboard: resources/js/Pages/Admin/Dashboard.vue
-   [x] Create Patient Dashboard: resources/js/Pages/Patient/Dashboard.vue (copy from current Dashboard.vue)
-   [x] Update routes/web.php: Modify /dashboard route to conditionally render based on user role
-   [x] Fix typo in Doctor/Dashboard.vue: Correct AuthenticatedLayout
-   [x] Remove original Dashboard.vue: After creating Patient/Dashboard.vue

## Followup Steps

-   [ ] Test application: Ensure users are redirected to correct dashboard based on role
-   [ ] Verify middleware: Confirm role-based access still works for other routes
