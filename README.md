# Headless CMS

## Description

This project is a Headless CMS developed with **Symfony 7.1** (compatible with 7.2) and **PHP 8.3**, designed to enable the creation, management, and distribution of content via a secure REST API. This CMS provides a centralized solution for content management while allowing dynamic consumption by various front-ends (web apps, mobile apps, etc.).

## Key Features

### User Management
- **Administrators**:
    - Create, edit, and delete content.
    - Manage users (edit and delete).
    - Delete comments.
- **Subscribers**:
    - Read content.
    - Manage their own comments (create, edit, delete).
- **Visitors**:
    - Read content without authentication.

### Content Management
- Manage articles with the following fields:
    - Title.
    - Cover image.
    - Meta tags (`title` and `description`).
    - Detailed content.
    - Auto-generated unique slug.
    - Associated tags.
    - Author.

### Comment Management
- Create and edit comments associated with content.
- Track the author and the content of each comment.

### Technical Features
- Use **UUIDs** to uniquely identify entities.
- Track creation and modification dates for all entities using reusable traits.
- Secure REST API for all data interactions.

## Requirements

### Technical Environment
- **PHP**: Version 8.3 or higher.
- **Symfony**: Version 7.1 or 7.2.
- **Database**: MySQL/MariaDB with UUID support.
- **Dependency Manager**: Composer.

### Required PHP Extensions
- `ext-json`
- `ext-pdo`
- `ext-mbstring`

## Installation

1. Clone this repository:
   ```bash
   git clone https://github.com/your-username/headless-cms.git
   cd headless-cms
   
