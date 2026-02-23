# Revenue

Revenue is a comprehensive venue management and booking platform designed to connect users with the perfect spaces for their events.

## 🚀 Getting Started

To get the project up and running on your local machine, please follow the detailed instructions in our setup guide:
👉 [Setup & Installation Instructions](docs/SETUP_GUIDE.md)

The system runs using Docker containers, providing a consistent environment for both the frontend and backend services:

![Docker Containers](images/containers.png)

## 🌐 Accessing the Platform

- **Regular User Interface:** [http://localhost:3005/](http://localhost:3005/)
- **Admin Dashboard:** [http://localhost:3005/admin/dashboard](http://localhost:3005/admin/dashboard)

## 👤 User Roles & Features

The platform supports two main types of users, each with specific capabilities:

### Admin User
Administrators have full control over the platform's content and can manage the following via the **Admin Dashboard** ([http://localhost:3005/admin/dashboard](http://localhost:3005/admin/dashboard)):
- **Categories**: Create, edit, and delete venue categories.
- **Venues**: Manage venue listings, including details, pricing, and capacity.
- **Quotes**: Review and manage quote requests submitted by users.

**Admin Login Credentials (Default):**
- **Email:** `admin@example.com`
- **Password:** `password`

### Regular User
Registered users can explore and interact with venue listings at [http://localhost:3005/](http://localhost:3005/):
- **Registration & Login**: Create an account to access personalized features.
- **Search & Filter**: Find venues by name, location, or category.
- **Favorites**: Save your favorite venues to a dedicated list for easy access.
- **Request Quote**: Submit quote requests for specific venues to get pricing and availability.
- **My Quotes**: View and track the status of your submitted quote requests.

**Test User Credentials (Default):**
- **Email:** `user@example.com`
- **Password:** `password`

### Platform Preview
![VenueSpace Demo](images/VenueSpace.gif)

## 🛠 Tech Stack
- **Backend:** Laravel (PHP)
- **Frontend:** Next.js (TypeScript)
- **Database:** MySQL
- **Styling:** Tailwind CSS
- **Map Integration:** Leaflet
