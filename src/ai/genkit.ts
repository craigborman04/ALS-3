import {genkit} from 'genkit';
import {googleAI} from '@genkit-ai/googleai';

export const ai = genkit({
  plugins: [googleAI({projectId: 'shizentai-aikikai'})],
  model: 'googleai/gemini-pro',
});
