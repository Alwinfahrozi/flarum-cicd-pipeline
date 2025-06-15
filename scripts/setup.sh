#!/bin/bash
# =====================================================
# Flarum Deployment Script
# Muh Dzaky Musaddaq - Docker/Deployment Engineer
# =====================================================

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Configuration
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_ROOT="$(dirname "$SCRIPT_DIR")"
DOCKER_COMPOSE_FILE="$PROJECT_ROOT/docker/docker-compose.yml"
ENV_FILE="$PROJECT_ROOT/.env.docker"

# Functions
log_info() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

log_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

log_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

log_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

check_requirements() {
    log_info "Checking deployment requirements..."
    
    # Check Docker
    if ! command -v docker &> /dev/null; then
        log_error "Docker is not installed or not in PATH"
        exit 1
    fi
    
    # Check Docker Compose
    if ! command -v docker-compose &> /dev/null; then
        log_error "Docker Compose is not installed or not in PATH"
        exit 1
    fi
    
    # Check if Docker daemon is running
    if ! docker info &> /dev/null; then
        log_error "Docker daemon is not running"
        exit 1
    fi
    
    log_success "All requirements satisfied"
}

create_directories() {
    log_info "Creating necessary directories..."
    
    mkdir -p "$PROJECT_ROOT/docker/nginx"
    mkdir -p "$PROJECT_ROOT/docker/php"
    mkdir -p "$PROJECT_ROOT/docker/mysql/init"
    mkdir -p "$PROJECT_ROOT/docker/mysql/conf"
    mkdir -p "$PROJECT_ROOT/storage/logs"
    mkdir -p "$PROJECT_ROOT/storage/cache"
    mkdir -p "$PROJECT_ROOT/storage/sessions"
    
    log_success "Directories created"
}

setup_environment() {
    log_info "Setting up environment configuration..."
    
    if [ ! -f "$ENV_FILE" ]; then
        log_warning "Docker environment file not found, creating default..."
        cp "$PROJECT_ROOT/.env.example" "$ENV_FILE"
        
        # Update for Docker environment
        sed -i 's/DB_HOST=.*/DB_HOST=mysql/' "$ENV_FILE"
        sed -i 's/REDIS_HOST=.*/REDIS_HOST=redis/' "$ENV_FILE"
        sed -i 's/APP_ENV=.*/APP_ENV=production/' "$ENV_FILE"
    fi
    
    log_success "Environment configuration ready"
}

build_images() {
    log_info "Building Docker images..."
    
    cd "$PROJECT_ROOT"
    
    # Build main application image
    docker-compose -f "$DOCKER_COMPOSE_FILE" build --no-cache
    
    log_success "Docker images built successfully"
}

start_services() {
    log_info "Starting services..."
    
    cd "$PROJECT_ROOT"
    
    # Start all services
    docker-compose -f "$DOCKER_COMPOSE_FILE" up -d
    
    log_success "Services started"
}

wait_for_services() {
    log_info "Waiting for services to be ready..."
    
    # Wait for MySQL
    log_info "Waiting for MySQL to be ready..."
    for i in {1..30}; do
        if docker-compose -f "$DOCKER_COMPOSE_FILE" exec -T mysql mysqladmin ping -h localhost --silent; then
            log_success "MySQL is ready"
            break
        fi
        if [ $i -eq 30 ]; then
            log_error "MySQL failed to start within 60 seconds"
            exit 1
        fi
        sleep 2
    done
    
    # Wait for Redis
    log_info "Waiting for Redis to be ready..."
    for i in {1..15}; do
        if docker-compose -f "$DOCKER_COMPOSE_FILE" exec -T redis redis-cli ping | grep -q PONG; then
            log_success "Redis is ready"
            break
        fi
        if [ $i -eq 15 ]; then
            log_error "Redis failed to start within 30 seconds"
            exit 1
        fi
        sleep 2
    done
    
    # Wait for Flarum application
    log_info "Waiting for Flarum application to be ready..."
    for i in {1..20}; do
        if curl -f http://localhost/health &>/dev/null; then
            log_success "Flarum application is ready"
            break
        fi
        if [ $i -eq 20 ]; then
            log_warning "Flarum application health check failed, but continuing..."
            break
        fi
        sleep 3
    done
}

run_health_checks() {
    log_info "Running health checks..."
    
    # Check container status
    if ! docker-compose -f "$DOCKER_COMPOSE_FILE" ps | grep -q "Up"; then
        log_error "Some containers are not running properly"
        docker-compose -f "$DOCKER_COMPOSE_FILE" ps
        exit 1
    fi
    
    # Check database connection
    if ! docker-compose -f "$DOCKER_COMPOSE_FILE" exec -T mysql mysqladmin ping -h localhost --silent; then
        log_error "Database health check failed"
        exit 1
    fi
    
    # Check Redis connection
    if ! docker-compose -f "$DOCKER_COMPOSE_FILE" exec -T redis redis-cli ping | grep -q PONG; then
        log_error "Redis health check failed"
        exit 1
    fi
    
    log_success "All health checks passed"
}

show_deployment_info() {
    echo ""
    log_success "🎉 Deployment completed successfully!"
    echo ""
    log_info "📋 Deployment Information:"
    echo "  🌐 Application URL: http://localhost"
    echo "  🗄️  Database Admin: http://localhost:8080 (if Adminer enabled)"
    echo "  📊 Container Status:"
    docker-compose -f "$DOCKER_COMPOSE_FILE" ps
    echo ""
    log_info "📝 Useful Commands:"
    echo "  Stop services:    docker-compose -f $DOCKER_COMPOSE_FILE down"
    echo "  View logs:        docker-compose -f $DOCKER_COMPOSE_FILE logs -f"
    echo "  Check status:     docker-compose -f $DOCKER_COMPOSE_FILE ps"
    echo ""
}

cleanup_on_error() {
    log_error "Deployment failed. Cleaning up..."
    docker-compose -f "$DOCKER_COMPOSE_FILE" down || true
    exit 1
}

# Main deployment function
main() {
    log_info "🚀 Starting Flarum deployment..."
    echo ""
    
    # Set trap for cleanup on error
    trap cleanup_on_error ERR
    
    # Run deployment steps
    check_requirements
    create_directories
    setup_environment
    build_images
    start_services
    wait_for_services
    run_health_checks
    show_deployment_info
    
    log_success "✅ Deployment completed successfully!"
}

# Handle command line arguments
case "${1:-deploy}" in
    "deploy")
        main
        ;;
    "stop")
        log_info "Stopping services..."
        docker-compose -f "$DOCKER_COMPOSE_FILE" down
        log_success "Services stopped"
        ;;
    "restart")
        log_info "Restarting services..."
        docker-compose -f "$DOCKER_COMPOSE_FILE" restart
        log_success "Services restarted"
        ;;
    "logs")
        docker-compose -f "$DOCKER_COMPOSE_FILE" logs -f
        ;;
    "status")
        docker-compose -f "$DOCKER_COMPOSE_FILE" ps
        ;;
    "clean")
        log_info "Cleaning up deployment..."
        docker-compose -f "$DOCKER_COMPOSE_FILE" down -v --remove-orphans
        log_success "Cleanup completed"
        ;;
    *)
        echo "Usage: $0 {deploy|stop|restart|logs|status|clean}"
        echo ""
        echo "Commands:"
        echo "  deploy   - Deploy the application (default)"
        echo "  stop     - Stop all services"
        echo "  restart  - Restart all services"
        echo "  logs     - Show and follow logs"
        echo "  status   - Show container status"
        echo "  clean    - Stop and remove all containers and volumes"
        exit 1
        ;;
esac