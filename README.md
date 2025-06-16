# Flarum CI/CD Pipeline

[![CI/CD Pipeline](https://github.com/Alwinfahrozi/flarum-cicd-pipeline/actions/workflows/ci.yml/badge.svg)](https://github.com/Alwinfahrozi/flarum-cicd-pipeline/actions/workflows/ci.yml)
[![Docker Build](https://github.com/Alwinfahrozi/flarum-cicd-pipeline/actions/workflows/cd.yml/badge.svg)](https://github.com/Alwinfahrozi/flarum-cicd-pipeline/actions/workflows/cd.yml)

## 📋 Project Overview

**Tugas Besar Manajemen Konfigurasi dan Evolusi Perangkat Lunak**

Implementasi lengkap CI/CD pipeline untuk aplikasi forum Flarum menggunakan GitHub Actions, Docker, dan automated testing. Project ini mendemonstrasikan penerapan modern DevOps practices dalam software development lifecycle.

### 👥 Team Members

| Name | Role | Components |
|------|------|------------|
| **Alwin Fahrozi Marbun** | CI/Testing Engineer | • Continuous Integration<br>• Continuous Testing |
| **Muh Dzaky Musaddaq** | Docker/Deployment Engineer | • Continuous Integration<br>• Continuous Deployment/Delivery |

### 🎯 Project Objectives

- ✅ Implement complete CI/CD pipeline using GitHub Actions
- ✅ Apply all four core components: CI, CT, CD, and quality automation
- ✅ Deploy web-based application with Docker containerization
- ✅ Demonstrate professional DevOps practices and collaboration

## 🏗️ Architecture Overview

```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│   Developer     │    │   GitHub        │    │   Production    │
│   Local Dev     │───▶│   Repository    │───▶│   Environment   │
└─────────────────┘    └─────────────────┘    └─────────────────┘
                              │
                              ▼
                    ┌─────────────────┐
                    │  GitHub Actions │
                    │   CI/CD Pipeline │
                    └─────────────────┘
                              │
                    ┌─────────┴─────────┐
                    ▼                   ▼
            ┌──────────────┐    ┌──────────────┐
            │ CI Pipeline  │    │ CD Pipeline  │
            │ (Alwin)      │    │ (Dzaky)      │
            └──────────────┘    └──────────────┘
```

## 🔧 Technology Stack

### **Application Layer**
- **Framework**: Flarum Forum (PHP-based)
- **Language**: PHP 8.2
- **Database**: MySQL 8.0
- **Cache**: Redis 7
- **Web Server**: Nginx

### **CI/CD Tools**
- **Platform**: GitHub Actions
- **Testing**: PHPUnit 10.5
- **Containerization**: Docker & Docker Compose
- **Package Manager**: Composer
- **Version Control**: Git

### **Infrastructure**
- **Orchestration**: Docker Compose
- **Registry**: GitHub Container Registry (GHCR)
- **Environment**: Ubuntu Latest (GitHub Runners)

## 📁 Project Structure

```
flarum-cicd-pipeline/
├── .github/workflows/          # GitHub Actions workflows
│   ├── ci.yml                 # Continuous Integration (Alwin)
│   └── cd.yml                 # Continuous Deployment (Dzaky)
├── docker/                    # Docker configuration (Dzaky)
│   ├── Dockerfile            # Container definition
│   ├── docker-compose.yml    # Service orchestration
│   ├── nginx/               # Web server config
│   ├── php/                 # PHP configuration
│   └── supervisord.conf     # Process manager
├── src/                      # Flarum application source
├── tests/                    # Test suites (Alwin)
│   ├── Unit/                # Unit tests
│   └── Feature/             # Integration tests
├── scripts/                  # Automation scripts
│   └── deploy.sh            # Deployment automation (Dzaky)
├── .env.example             # Environment template
├── .env.docker              # Docker environment
├── composer.json            # PHP dependencies
├── phpunit.xml              # Testing configuration (Alwin)
└── README.md               # Project documentation
```

## 🚀 Getting Started

### Prerequisites

- **Git** (version 2.30+)
- **Docker** (version 20.10+)
- **Docker Compose** (version 2.0+)
- **PHP** 8.2+ (for local development)
- **Composer** (latest version)

### 1. Clone Repository

```bash
git clone https://github.com/Alwinfahrozi/flarum-cicd-pipeline.git
cd flarum-cicd-pipeline
```

### 2. Environment Setup

```bash
# Copy environment template
cp .env.example .env

# Configure environment variables
# Edit .env file with your database credentials and application settings
```

### 3. Local Development Setup

```bash
# Install PHP dependencies
composer install

# Run tests
composer test

# Check code quality
composer quality
```

### 4. Docker Deployment

```bash
# Build and start services
./scripts/deploy.sh

# Or using Docker Compose directly
docker-compose -f docker/docker-compose.yml up -d

# Check service status
docker-compose -f docker/docker-compose.yml ps
```

### 5. Access Application

- **Application**: http://localhost
- **Database Admin**: http://localhost:8080 (Adminer)
- **Health Check**: http://localhost/health

## 🧪 Testing Framework (Alwin's Component)

### **Continuous Integration Features**
- **Multi-environment testing**: PHP 8.1, 8.2, 8.3
- **Automated quality gates**: Code style, static analysis
- **Database integration**: MySQL service testing
- **Comprehensive coverage**: 30+ test cases

### **Test Suites**

#### Unit Tests
```bash
# Run unit tests only
./vendor/bin/phpunit --testsuite=Unit

# With coverage
./vendor/bin/phpunit --testsuite=Unit --coverage-html coverage
```

#### Feature Tests
```bash
# Run integration tests
./vendor/bin/phpunit --testsuite=Feature

# All tests with detailed output
./vendor/bin/phpunit --testdox
```

### **Quality Automation**
```bash
# Run all quality checks
composer quality

# Individual commands
composer test           # PHPUnit testing
composer analyze        # Static analysis
composer format         # Code formatting
```

## 🐳 Docker Deployment (Dzaky's Component)

### **Continuous Deployment Features**
- **Multi-stage builds**: Optimized container images
- **Service orchestration**: MySQL, Redis, Nginx integration
- **Health monitoring**: Automated health checks
- **Security scanning**: Vulnerability assessment

### **Container Architecture**

```
┌─────────────────┐
│   Load Balancer │ (Nginx)
└─────────┬───────┘
          │
┌─────────▼───────┐
│  Flarum App     │ (PHP-FPM + Nginx)
└─────────┬───────┘
          │
    ┌─────┴─────┐
    ▼           ▼
┌─────────┐ ┌─────────┐
│  MySQL  │ │  Redis  │
│Database │ │ Cache   │
└─────────┘ └─────────┘
```

### **Deployment Commands**

```bash
# Deploy application
./scripts/deploy.sh

# Stop services
./scripts/deploy.sh stop

# View logs
./scripts/deploy.sh logs

# Check status
./scripts/deploy.sh status

# Clean deployment
./scripts/deploy.sh clean
```

### **Production Deployment**

```bash
# Production environment
docker-compose -f docker/docker-compose.yml --profile production up -d

# Development environment
docker-compose -f docker/docker-compose.yml --profile development up -d
```

## ⚡ CI/CD Pipeline Workflow

### **1. Continuous Integration (Triggered on Push/PR)**

```yaml
┌─────────────────┐
│ Code Quality    │ ✅ Composer validation
│ & Static        │ ✅ Dependency caching
│ Analysis        │ ✅ Security checks
└─────────────────┘
         │
         ▼
┌─────────────────┐
│ Unit & Feature  │ ✅ PHPUnit testing
│ Testing         │ ✅ Coverage reporting
│                 │ ✅ Multi-PHP versions
└─────────────────┘
         │
         ▼
┌─────────────────┐
│ Integration     │ ✅ Database testing
│ Testing         │ ✅ Redis integration
│                 │ ✅ Environment validation
└─────────────────┘
         │
         ▼
┌─────────────────┐
│ Build Status    │ ✅ Success notification
│ Summary         │ ✅ Artifact archiving
│                 │ ✅ Quality reports
└─────────────────┘
```

### **2. Continuous Deployment (Triggered after CI Success)**

```yaml
┌─────────────────┐
│ Build Docker    │ ✅ Multi-platform builds
│ Image           │ ✅ Registry push
│                 │ ✅ Metadata tagging
└─────────────────┘
         │
         ▼
┌─────────────────┐
│ Deploy to       │ ✅ Service orchestration
│ Development     │ ✅ Health checks
│                 │ ✅ Smoke testing
└─────────────────┘
         │
         ▼
┌─────────────────┐
│ Security        │ ✅ Vulnerability scanning
│ Scan            │ ✅ Dependency audit
│                 │ ✅ Security reporting
└─────────────────┘
         │
         ▼
┌─────────────────┐
│ Deployment      │ ✅ Status summary
│ Summary         │ ✅ Success notification
│                 │ ✅ Environment URLs
└─────────────────┘
```

## 📊 Monitoring & Logging

### **Health Checks**
- **Application**: HTTP health endpoint (`/health`)
- **Database**: MySQL connection validation
- **Cache**: Redis connectivity check
- **Services**: Docker container status monitoring

### **Logging Strategy**
- **Application logs**: `/var/log/nginx/` and `/var/log/supervisor/`
- **Build logs**: GitHub Actions workflow logs
- **Container logs**: `docker-compose logs -f`

### **Metrics & Monitoring**
```bash
# Check application health
curl http://localhost/health

# Monitor container resources
docker stats

# View real-time logs
docker-compose -f docker/docker-compose.yml logs -f flarum
```

## 🔒 Security Features

### **Container Security**
- **Non-root user**: Application runs as `www` user
- **Minimal base image**: Alpine Linux for reduced attack surface
- **Security headers**: Nginx security configuration
- **Vulnerability scanning**: Automated Trivy security scans

### **Application Security**
- **Environment isolation**: Separate development/production configs
- **Secrets management**: Environment variable protection
- **Database security**: User privilege separation
- **Network isolation**: Docker network segmentation

## 📈 Performance Optimization

### **Application Performance**
- **OpCache**: PHP acceleration enabled
- **Redis caching**: Session and data caching
- **Nginx optimization**: Gzip compression and static file caching
- **Database tuning**: MySQL 8.0 optimizations

### **Build Performance**
- **Layer caching**: Docker build cache optimization
- **Parallel builds**: GitHub Actions matrix strategy
- **Dependency caching**: Composer and npm cache strategies

## 🛠️ Development Workflow

### **Local Development**
1. **Clone repository** and setup environment
2. **Install dependencies** with Composer
3. **Run tests** to ensure functionality
4. **Make changes** and commit with meaningful messages
5. **Push to branch** to trigger CI pipeline
6. **Create PR** for code review and integration

### **Team Collaboration**
- **Branch protection**: Require PR reviews and status checks
- **Automated testing**: All commits trigger CI pipeline
- **Code quality gates**: Automated formatting and analysis
- **Documentation**: Comprehensive README and inline comments

## 🚀 Deployment Strategies

### **Development Environment**
```bash
# Quick development setup
./scripts/deploy.sh

# Access development tools
open http://localhost:8080  # Adminer database admin
```

### **Production Environment**
```bash
# Production deployment
docker-compose -f docker/docker-compose.yml --profile production up -d

# SSL/TLS configuration
# Configure reverse proxy with SSL certificates
```

### **Scaling Strategies**
- **Horizontal scaling**: Multiple application containers
- **Database scaling**: Read replicas and connection pooling
- **Cache optimization**: Redis clustering for high availability
- **Load balancing**: Nginx upstream configuration

## 📚 Additional Resources

### **Documentation Links**
- [Flarum Documentation](https://docs.flarum.org/)
- [Docker Compose Reference](https://docs.docker.com/compose/)
- [GitHub Actions Documentation](https://docs.github.com/en/actions)
- [PHPUnit Testing Guide](https://phpunit.de/documentation.html)

### **Useful Commands**

```bash
# Development
composer install              # Install dependencies
composer test                 # Run test suite
composer quality              # Run quality checks

# Docker
docker-compose up -d          # Start services
docker-compose down           # Stop services
docker-compose logs -f        # View logs

# Deployment
./scripts/deploy.sh           # Deploy application
./scripts/deploy.sh status    # Check deployment status
./scripts/deploy.sh clean     # Clean deployment
```

## 🐛 Troubleshooting

### **Common Issues**

#### Docker Build Failures
```bash
# Clear Docker cache
docker system prune -a

# Rebuild without cache
docker-compose build --no-cache
```

#### Test Failures
```bash
# Run specific test
./vendor/bin/phpunit tests/Unit/ExampleTest.php

# Debug test output
./vendor/bin/phpunit --debug
```

#### Permission Issues
```bash
# Fix file permissions
sudo chown -R $USER:$USER storage/
chmod -R 755 storage/
```

### **Getting Help**
- **GitHub Issues**: Report bugs and feature requests
- **Team Communication**: Contact team members for support
- **Documentation**: Check this README and inline comments

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🙏 Acknowledgments

- **Flarum Community** for the excellent forum framework
- **GitHub Actions** for robust CI/CD platform
- **Docker Community** for containerization tools
- **Course Instructor** for guidance and requirements
- **Team Members** for collaboration and dedication

---

**Built by Alwin Fahrozi Marbun & Muh Dzaky Musaddaq**

*Tugas Besar Manajemen Konfigurasi dan Evolusi Perangkat Lunak*