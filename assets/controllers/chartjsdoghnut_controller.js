import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    chart;
    total = 0; // Total of all data points
    currentlyActiveSegmentIndex = null; // Track the currently active segment index

    connect() {
        this.element.addEventListener('chartjs:connect', this._onConnect);
    }

    disconnect() {
        this.element.removeEventListener('chartjs:connect', this._onConnect);
    }

    _onConnect(event) {
        //console.log('ChartjsDoughnutController#_onConnect',event);
        this.chart = event.detail.chart;

        // Calculate total once
        this.total = this.chart.data.datasets[0].data.reduce((acc, val) => acc + val, 0);

        const drawDataInCenter = (data, labeldata, color, percentage) => {
            const ctx = this.chart.ctx;
            const centerX = (this.chart.chartArea.left + this.chart.chartArea.right) / 2;
            const centerY = (this.chart.chartArea.top + this.chart.chartArea.bottom) / 2;

            // Clear previous drawing
            ctx.clearRect(centerX - 100, centerY - 50, 200, 100); // Adjusted for responsiveness

            // Draw the label and data
            ctx.font = '16px Arial'; // Adjust font size as needed
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            ctx.fillStyle = color;
            ctx.backgroundColor = "#ffffff00";
            ctx.fillText(labeldata + ': ' + data, centerX, centerY - 10);
            ctx.fillText(percentage, centerX, centerY + 10); // Percentage on the next line
        };

        this.chart.options.onClick = (mouseEvent, chartElements) => {
            if (chartElements.length) {
                const index = chartElements[0].index;

                // Only redraw if a different segment is clicked
                if (this.currentlyActiveSegmentIndex !== index) {
                    this.currentlyActiveSegmentIndex = index;
                    const dataset = this.chart.data.datasets[0];

                    if (dataset.data[index] !== undefined && this.chart.data.labels[index] !== undefined) {
                        const data = dataset.data[index];
                        const labeldata = this.chart.data.labels[index];
                        const color = dataset.backgroundColor[index];
                        const percentage = ((data * 100) / this.total).toFixed(2) + '%';
                        drawDataInCenter(data, labeldata, color, percentage);
                    }
                }
            } else {
                // Reset the selected index and clear the center if clicked outside of segments
                if (this.currentlyActiveSegmentIndex !== null) {
                    this.currentlyActiveSegmentIndex = null;
                    const ctx = this.chart.ctx;
                    const centerX = (this.chart.chartArea.left + this.chart.chartArea.right) / 2;
                    const centerY = (this.chart.chartArea.top + this.chart.chartArea.bottom) / 2;
                    ctx.clearRect(centerX - 100, centerY - 50, 200, 100); // Adjusted for responsiveness
                }
            }
        };
    }
}
