# gRPC with Monorepo

A microservices architecture demonstration using gRPC for inter-service communication, built with a monorepo structure containing multiple services written in different technologies.

## Table of Contents

- [Introduction to gRPC](#introduction-to-grpc)
- [Why Use gRPC?](#why-use-grpc)
- [Project Structure](#project-structure)
- [Project Setup & Installation](#project-setup--installation)
- [Summary](#summary)

---

## Introduction to gRPC

gRPC (gRPC Remote Procedure Call) is a modern, open-source, high-performance Remote Procedure Call (RPC) framework developed by Google. It enables client and server applications to communicate transparently and build connected systems more easily.

### Key Features:

- **Protocol Buffers**: Uses Protocol Buffers (protobuf) as the Interface Definition Language (IDL) for defining service contracts and message structures
- **HTTP/2 Based**: Built on HTTP/2, providing benefits like multiplexing, flow control, header compression, and bidirectional streaming
- **Language Agnostic**: Supports multiple programming languages including Node.js, PHP, Python, Java, Go, and more
- **Strongly Typed**: Enforces type safety through protobuf definitions, reducing runtime errors
- **Efficient Serialization**: Binary serialization is faster and more compact than JSON or XML

---

## Why Use gRPC?

### 1. **Performance**
- Binary serialization with Protocol Buffers is significantly faster than JSON
- HTTP/2 multiplexing allows multiple requests over a single connection
- Reduced payload size leads to lower bandwidth consumption

### 2. **Type Safety**
- Strongly typed contracts defined in `.proto` files
- Automatic code generation for client and server stubs
- Compile-time validation prevents many runtime errors

### 3. **Streaming Support**
- Unary RPC (single request/response)
- Server streaming (single request, stream of responses)
- Client streaming (stream of requests, single response)
- Bidirectional streaming (both client and server stream)

### 4. **Language Interoperability**
- Services written in different languages can communicate seamlessly
- This project demonstrates Node.js (NestJS) and PHP (Laravel) services communicating via gRPC

### 5. **Built-in Features**
- Authentication and authorization
- Load balancing
- Deadlines/timeouts
- Cancellation and error handling

### 6. **Microservices Architecture**
- Ideal for service-to-service communication
- Efficient for internal APIs where performance matters
- Better suited than REST for high-throughput, low-latency scenarios

---

## Project Structure

```
gRPC-Monorepo/
│
├── auth-service/                 # NestJS gRPC Authentication Service
│   ├── src/
│   │   ├── grpc/
│   │   │   └── protos/
│   │   │       └── authService.proto    # gRPC service definition
│   │   ├── app/
│   │   │   └── modules/
│   │   │       └── auth/
│   │   │           └── auth.controller.ts
│   │   ├── app.module.ts
│   │   └── main.ts              # gRPC server setup (port 50051)
│   ├── Dockerfile
│   ├── package.json
│   └── tsconfig.json
│
├── gRPCServiceA/                # Laravel Service (gRPC Client)
│   ├── app/
│   │   ├── Http/
│   │   │   ├── Controllers/
│   │   │   └── Middleware/
│   │   │       └── EnsureTokenIsValid.php
│   │   ├── Services/
│   │   │   └── AuthService.php  # gRPC client implementation
│   │   └── protos/
│   │       └── auth/
│   │           ├── AuthService.proto
│   │           └── AuthService/  # Generated PHP classes
│   ├── Dockerfile
│   ├── composer.json
│   └── routes/
│
├── gRPCServiceB/                # Laravel Service (Additional Service)
│   ├── app/
│   ├── Dockerfile
│   └── composer.json
│
├── docker-compose.yml           # Docker orchestration
└── README.md
```

### Service Architecture:

1. **auth-service** (Port 50051)
   - Technology: Node.js with NestJS
   - Role: gRPC Server providing authentication services
   - Exposes: `AuthCheck` RPC method for token validation

2. **gRPCServiceA** (Port 8000)
   - Technology: PHP with Laravel 12
   - Role: HTTP API service that acts as gRPC client
   - Features: Token validation middleware using gRPC calls to auth-service

3. **gRPCServiceB** (Port 8001)
   - Technology: PHP with Laravel 12
   - Role: Additional HTTP API service

4. **mysql** (Port 3307)
   - Database service for all applications

5. **phpmyadmin** (Port 8080)
   - Database management interface

---

## Project Setup & Installation

### Prerequisites

- Docker and Docker Compose installed
- Git installed
- Ports 8000, 8001, 8080, 3307, and 50051 available

### Installation Steps

#### 1. Clone the Repository

```bash
git clone <repository-url>
cd gRPC-Monorepo
```

#### 2. Configure Environment Variables

**For auth-service:**
```bash
cd auth-service
cp .env.example .env
# Edit .env with your configuration
cd ..
```

**For gRPCServiceA:**
```bash
cd gRPCServiceA
cp .env.example .env
# Edit .env with your database and gRPC configuration
cd ..
```

**For gRPCServiceB:**
```bash
cd gRPCServiceB
cp .env.example .env
# Edit .env with your configuration
cd ..
```

#### 3. Build and Start Services

```bash
# Build all Docker containers
docker-compose build

# Start all services
docker-compose up -d
```

#### 4. Install Dependencies

**For auth-service (NestJS):**
```bash
docker exec -it authservice sh
npm install
npm run build
exit
```

**For gRPCServiceA (Laravel):**
```bash
docker exec -it grpcservicea bash
composer install
php artisan key:generate
php artisan migrate
exit
```

**For gRPCServiceB (Laravel):**
```bash
docker exec -it grpcserviceb bash
composer install
php artisan key:generate
php artisan migrate
exit
```

#### 5. Generate gRPC Code (if needed)

**For auth-service:**
```bash
docker exec -it authservice sh
npm run generate:proto
exit
```

**For gRPCServiceA:**
```bash
docker exec -it grpcservicea bash
# Generate PHP classes from proto files
protoc --php_out=app/protos/auth \
       --grpc_out=app/protos/auth \
       --plugin=protoc-gen-grpc=grpc/grpc_php_plugin \
       app/protos/auth/AuthService.proto
exit
```

### Verify Installation

1. **Check all containers are running:**
```bash
docker-compose ps
```

2. **Access services:**
   - gRPCServiceA: http://localhost:8000
   - gRPCServiceB: http://localhost:8001
   - phpMyAdmin: http://localhost:8080
   - auth-service gRPC: localhost:50051

3. **Test gRPC communication:**
```bash
# From gRPCServiceA, test authentication
curl -X POST http://localhost:8000/api/test-auth \
  -H "Content-Type: application/json" \
  -d '{"token": "your-test-token"}'
```

### Development Workflow

**Start services:**
```bash
docker-compose up -d
```

**View logs:**
```bash
# All services
docker-compose logs -f

# Specific service
docker-compose logs -f authservice
docker-compose logs -f grpcservicea
```

**Stop services:**
```bash
docker-compose down
```

**Rebuild after changes:**
```bash
docker-compose down
docker-compose build
docker-compose up -d
```

### Troubleshooting

**gRPC connection issues:**
- Ensure auth-service is running: `docker-compose ps authservice`
- Check auth-service logs: `docker-compose logs authservice`
- Verify network connectivity: `docker network inspect monorepo`

**Database connection issues:**
- Ensure MySQL is running: `docker-compose ps mysql`
- Check database credentials in `.env` files
- Wait for MySQL to fully initialize (may take 30-60 seconds on first run)

**Port conflicts:**
- Check if ports are already in use: `netstat -an | grep LISTEN`
- Modify port mappings in `docker-compose.yml` if needed

---

## Summary

This project demonstrates a modern microservices architecture using gRPC for efficient inter-service communication. The monorepo structure contains:

- **Multi-language support**: Node.js (NestJS) and PHP (Laravel) services communicating seamlessly
- **gRPC implementation**: High-performance RPC calls for authentication between services
- **Containerized deployment**: Docker Compose orchestration for easy development and deployment
- **Scalable architecture**: Services can be independently scaled and deployed
- **Type-safe contracts**: Protocol Buffers ensure consistent API contracts across services

### Key Takeaways:

✅ gRPC provides superior performance compared to REST for service-to-service communication  
✅ Protocol Buffers enable language-agnostic, type-safe service definitions  
✅ Monorepo structure simplifies dependency management and code sharing  
✅ Docker containerization ensures consistent environments across development and production  
✅ The architecture supports horizontal scaling and independent service deployment  

### Use Cases:

This architecture is ideal for:
- Microservices requiring high-performance communication
- Polyglot systems with services in different languages
- Real-time applications needing low latency
- Systems with complex service dependencies
- Applications requiring strong API contracts

---

## License

MIT

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.
