# Eriník Portfolio

A collaborative portfolio website showcasing the projects and work of three developers: Níko, Inês, and Eric.

## About

This is a shared portfolio site built as part of a web development course project. The site features a modern, responsive design with custom CSS animations, SVG graphics, and dynamic content loading using XML/XSLT transformations.

## Team Members

- **Níko** - Web Development Projects
- **Inês Neves** - Frontend Development
- **Eric Henderson** - Full-Stack Development

## Features

- **Responsive Grid Layout** - Two-column grid showcasing multiple projects
- **Interactive Navigation** - Dropdown menus with smooth transitions
- **Custom SVG Graphics** - Hand-designed logo and footer decorations
- **Dynamic Content** - XML/XSLT for structured data presentation
- **Contact Form** - PHP-powered email functionality
- **Hover Effects** - Smooth CSS animations throughout
- **Mobile-Friendly** - Responsive design that adapts to different screen sizes

## Technologies Used

### Frontend
- HTML5
- CSS3 (Custom animations, Grid, Flexbox)
- JavaScript (ES6+)
- jQuery & jQuery Validation
- XML/XSLT

### Backend
- PHP (Email handling)
- Composer (Dependency management)

### Design
- Custom SVG graphics
- Responsive design principles
- Modern CSS techniques (overflow control, z-index layering)

## Project Structure

```
xml-jquery-final/
├── index.html              # Main portfolio page
├── pages/
│   ├── about.html          # About page
│   ├── contact.html        # Contact form
│   └── works.html          # Detailed works page
├── styles/
│   ├── main-design.css     # Global styles
│   ├── portfolio-style.css # Portfolio-specific styles
│   ├── about-style.css     # About page styles
│   └── works-style.css     # Works page styles
├── scripts/
│   ├── frontend/           # Client-side JavaScript
│   │   ├── connect.js      # Main connection logic
│   │   ├── contact.js      # Contact form handling
│   │   ├── about.js        # About page interactions
│   │   └── works.js        # Works page interactions
│   └── backend/
│       └── send-email.php  # Email processing
├── data/
│   ├── xslt/               # XSLT transformation files
│   │   ├── eric.xsl
│   │   ├── ines.xsl
│   │   └── niko.xsl
│   └── *.xml               # Data files for each contributor
└── assets/
    ├── images/             # Project screenshots and images
    └── icons/              # SVG icons

```

## Featured Projects

### Níko's Projects
- **Web Development Pathway** - A JavaScript website to assist BHCC students in determining required classes
- **New England States Information** - Interactive JavaScript website for viewing essential information on New England states

### Inês's Projects
- **Best Burger** - Online Burger Store website
- **Starbucks Clone** - Web-based clone of Starbucks website

### Eric's Projects
- **Erics48** - A Vue.js hiking app for documenting NH 48 4,000-footers list
  - Tech Stack: Vue.js, Tailwind CSS, Supabase
  - [Live Demo](https://erics48.vercel.app/)
- **Bonsai Sensor Dashboard** - Dashboard rendering daily temperature, humidity, and atmospheric pressure for bonsai trees
  - Tech Stack: React.js, Python, nohup pipeline, Supabase

## Installation & Setup

### Prerequisites
- Web server (Apache, Nginx, or local development server)
- PHP 7.4+ (for contact form functionality)
- Composer (for PHP dependencies)

### Local Development

1. Clone the repository:
```bash
git clone https://github.com/ehenderson828/xml-jquery-final.git
cd xml-jquery-final
```

2. Install PHP dependencies:
```bash
composer install
```

3. Set up email configuration:
   - Copy `scripts/email-config.php.example` to `scripts/email-config.php`
   - Add your SMTP credentials
   - See `EMAIL_SETUP.md` for detailed instructions

4. Start a local server:
```bash
# Using PHP built-in server
php -S localhost:8000

# Or using Python
python -m http.server 8000
```

5. Open your browser and navigate to `http://localhost:8000`

## Key CSS Improvements

This project includes several critical CSS fixes for optimal display:

- **Overflow Control** - Prevents horizontal scrolling and white space issues
- **Responsive Width Management** - Ensures proper viewport fitting on all devices
- **Footer Alignment** - SVG decorations properly positioned without cutoff
- **Z-index Layering** - Proper stacking context for dropdowns and interactive elements
- **Flexbox Layout** - Modern layout structure for better responsiveness

## Deployment

For deployment instructions, see `DEPLOYMENT.md`.

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)

## Contributing

This is a collaborative project for educational purposes. If you're a team member:

1. Create a feature branch from `main`
2. Make your changes
3. Test thoroughly
4. Create a pull request with detailed description
5. Wait for review and approval

## Recent Updates

- ✅ Fixed white space on left margin
- ✅ Resolved footer SVG clipping issues
- ✅ Added responsive navigation in footer
- ✅ Improved project box alignment
- ✅ Merged latest changes from all contributors
- ✅ Added author hover effects

## License

This project is created for educational purposes as part of a web development course.

## Contact

For questions or collaboration opportunities, please use the contact form on the website or reach out to any of the team members.

---

**Built with ❤️ by Eriník (Eric, Níko, & Inês)**
