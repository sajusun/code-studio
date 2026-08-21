# Backend Architecture

```mermaid
flowchart TD
    subgraph API
        C1[Controllers] --> C2[Services]
        C2 --> C3[Repositories]
        C3 --> C4[Models]
        C4 --> DB[(MySQL Database)]
        C2 --> E1[Events]
        E1 --> L1[Listeners]
        C2 --> J1[Jobs (Queue)]
    end
    Auth[Sanctum JWT] --> C1
    Cache[Redis Cache] --> C3
    Queue[Redis Queue] --> J1
    style API fill:#f9f9f9,stroke:#333,stroke-width:2px
    style Auth fill:#e3f2fd,stroke:#1565c0,stroke-width:1px
    style Cache fill:#e8f5e9,stroke:#2e7d32,stroke-width:1px
    style Queue fill:#fff3e0,stroke:#ef6c00,stroke-width:1px
```

This diagram illustrates the flow from HTTP request → Controller → Service → Repository → Model → Database, with supporting layers for authentication, caching, queuing, and event handling.
