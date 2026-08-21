@props([
    'type' => 'line', // line, bar, area, donut, pie
    'height' => 350,
    'series' => [],
    'categories' => [],
    'title' => null,
    'chartId' => 'chart-' . uniqid()
])

<div x-data="chartComponent_{{ str_replace('-', '_', $chartId) }}()" x-init="initChart()" class="w-full">
    <div x-ref="chart"></div>
</div>

@once
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
@endonce

<!-- We need separate Alpine component for each chart to avoid conflicts -->
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('chartComponent_{{ str_replace('-', '_', $chartId) }}', () => ({
            chart: null,
            initChart() {
                let options = {
                    series: @json($series),
                    chart: {
                        type: '{{ $type }}',
                        height: {{ $height }},
                        toolbar: { show: false },
                        fontFamily: 'inherit',
                        background: 'transparent'
                    },
                    @if($title)
                    title: {
                        text: '{{ $title }}',
                        align: 'left',
                        style: {
                            color: document.documentElement.classList.contains('dark') ? '#fff' : '#111827',
                            fontWeight: '600'
                        }
                    },
                    @endif
                    xaxis: {
                        categories: @json($categories),
                        labels: {
                            style: {
                                colors: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#6b7280'
                            }
                        },
                        axisBorder: { show: false },
                        axisTicks: { show: false }
                    },
                    yaxis: {
                        labels: {
                            style: {
                                colors: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#6b7280'
                            }
                        }
                    },
                    grid: {
                        borderColor: document.documentElement.classList.contains('dark') ? '#374151' : '#f3f4f6',
                        strokeDashArray: 4,
                    },
                    theme: {
                        mode: document.documentElement.classList.contains('dark') ? 'dark' : 'light'
                    },
                    colors: ['#4f46e5', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'],
                    dataLabels: {
                        enabled: false
                    }
                };

                this.chart = new ApexCharts(this.$refs.chart, options);
                this.chart.render();

                // Listen for dark mode toggle if applicable
                const observer = new MutationObserver(() => {
                    const isDark = document.documentElement.classList.contains('dark');
                    this.chart.updateOptions({
                        theme: { mode: isDark ? 'dark' : 'light' },
                        title: { style: { color: isDark ? '#fff' : '#111827' } },
                        xaxis: { labels: { style: { colors: isDark ? '#9ca3af' : '#6b7280' } } },
                        yaxis: { labels: { style: { colors: isDark ? '#9ca3af' : '#6b7280' } } },
                        grid: { borderColor: isDark ? '#374151' : '#f3f4f6' }
                    });
                });
                
                observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
            }
        }));
    });
</script>
