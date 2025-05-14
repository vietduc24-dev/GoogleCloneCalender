export interface EventType {
  id: number;
  title: string;
  start: Date | string;
  end: Date | string;
  type: 'meeting' | 'personal' | 'reminder' | 'other';
  location?: string;
  description?: string;
  color?: string;
}

// Sample events data
export const dummyEvents: EventType[] = [
  {
    id: 1,
    title: 'Team Meeting',
    start: new Date(2024, 2, 20, 10, 0),
    end: new Date(2024, 2, 20, 11, 0),
    type: 'meeting',
    location: 'Conference Room A',
    description: 'Weekly team sync'
  },
  {
    id: 2,
    title: 'Lunch Break',
    start: new Date(2024, 2, 20, 12, 0),
    end: new Date(2024, 2, 20, 13, 0),
    type: 'personal'
  },
  {
    id: 3,
    title: 'Project Deadline',
    start: new Date(2024, 2, 20, 15, 0),
    end: new Date(2024, 2, 20, 15, 30),
    type: 'reminder',
    description: 'Submit final project deliverables'
  }
]; 